<?php

namespace App\Http\View\Composers;

use App\Models\DocumentType;
use App\Models\Employee;
use Illuminate\View\View;

class EmployeeDocumentFormComposer
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
            'employees' => Employee::all(),
        ]);
    }
}
