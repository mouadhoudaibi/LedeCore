<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource (Admin).
     */
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Sanitize inputs
        $data['name'] = trim(strip_tags($data['name']));
        $data['description'] = $data['description'] ? trim(strip_tags($data['description'])) : null;
        $data['sku'] = strtoupper(trim($data['sku']));

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Ensure slug is unique
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Product::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['stock_quantity'] = $data['stock_quantity'] ?? 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', __('admin.product_created'));
    }

    /**
     * Display the specified resource (Admin).
     */
    public function show(Product $product): View
    {
        $product->load('category');

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $categories = Category::where('is_active', true)->get();
        $product->load('category');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        // Sanitize inputs
        $data['name'] = trim(strip_tags($data['name']));
        $data['description'] = $data['description'] ? trim(strip_tags($data['description'])) : null;
        $data['sku'] = strtoupper(trim($data['sku']));

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Ensure slug is unique (excluding current product)
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Product::where('slug', $data['slug'])->where('id', '!=', $product->id)->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['stock_quantity'] = $data['stock_quantity'] ?? 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', __('admin.product_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Check if product has orders
        if ($product->orderItems()->count() > 0) {
            return redirect()->route('admin.products.index')
                ->with('error', __('admin.product_has_orders'));
        }

        // Delete product image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', __('admin.product_deleted'));
    }

    /**
     * Display a listing of products (Public).
     */
    public function list(): View
    {
        $query = Product::with('category')->where('is_active', true);

        // Filter by category if provided
        if (request()->has('category') && request()->category) {
            $query->where('category_id', request()->category);
        }

        // Search by product name if provided
        if (request()->has('search') && request()->search) {
            $search = request()->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Display the specified product (Public).
     */
    public function detail(Product $product): View
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load('category');

        return view('products.show', compact('product'));
    }

    /**
     * Display the homepage.
     */
    public function home(): View
    {
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('home', compact('featuredProducts'));
    }
}
