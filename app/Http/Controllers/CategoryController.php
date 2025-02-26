<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;

class CategoryController extends Controller
{
    public function create()
    {
        return Inertia::render('Category/Form', ['parents' => Category::onlyParents()->get()]);
    }

    public function destroy(Category $category)
    {
        if ($category->del()) {
            return redirect()->route('categories.index')->with('message', __choice('action_text', ['record' => 'Category', 'action' => 'deleted']));
        }

        return back()->with('error', __('The record can not be deleted.'));
    }

    public function destroyPermanently(Category $category)
    {
        if ($category->delP()) {
            return redirect()->route('categories.index')->with('message', __choice('action_text', ['record' => 'Category', 'action' => 'permanently deleted']));
        }

        return back()->with('error', __('The record can not be deleted.'));
    }

    public function edit(Category $category)
    {
        return Inertia::render('Category/Form', [
            'parents' => Category::onlyParents()->get(),
            'edit'    => new CategoryResource($category),
        ]);
    }

    public function index(Request $request)
    {
        $filters = $request->all('search', 'parent', 'trashed');

        return Inertia::render('Category/Index', [
            'filters'    => $filters,
            'parents'    => Category::onlyParents()->get(),
            'categories' => new CategoryCollection(
                Category::with('parent:id,name,code')->filter($filters)->orderByDesc('id')->paginate()->withQueryString()
            ),
        ]);
    }

    public function restore(Category $category)
    {
        $category->restore();

        return back()->with('message', __choice('action_text', ['record' => 'Category', 'action' => 'restored']));
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')->with('message', __choice('action_text', ['record' => 'Category', 'action' => 'created']));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return back()->with('message', __choice('action_text', ['record' => 'Category', 'action' => 'updated']));
    }
}
