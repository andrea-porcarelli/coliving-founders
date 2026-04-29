<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{
    public function home()
    {
        return $this->renderPage('home');
    }

    public function show(Request $request, string $slug)
    {
        return $this->renderPage($slug);
    }

    private function renderPage(string $slug)
    {
        $page = Page::with('publishedSections')
            ->where('slug', $slug)
            ->where('published', true)
            ->first();

        if (! $page) {
            throw new NotFoundHttpException();
        }

        return view('pages.show', ['page' => $page]);
    }
}
