<?php

namespace App\Http\View\Composers;

use App\Models\Bond;
use App\Models\DocumentType;
use Illuminate\View\View;

class DocumentFormComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with([
            'documentTypes' => DocumentType::orderBy('name')->get(),
            'bonds' => Bond::all(),
        ]);
    }
}
