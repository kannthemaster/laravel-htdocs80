<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Visit;
use App\Models\VisitMedicine;
use Illuminate\Http\Request;

class VisitMedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $visitmedicine = VisitMedicine::where('visit_id', 'LIKE', "%$keyword%")
                ->orWhere('medicine', 'LIKE', "%$keyword%")
                ->orWhere('method', 'LIKE', "%$keyword%")
                ->orWhere('amount', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $visitmedicine = VisitMedicine::latest()->paginate($perPage);
        }

        return view('visit-medicine.index', compact('visitmedicine'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('visit-medicine.create');
    }

    public function add()
    {
        return view('visit-medicine.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
{
    $requestData = $request->all();

    if (is_array($requestData['medicine_id'])) {
        foreach ($requestData['medicine_id'] as $key => $value) {
            if ($requestData['medicine_id'][$key]) {
                VisitMedicine::create([
                    'visit_id' => $requestData['visit_id'],
                    'medicine_id' => $requestData['medicine_id'][$key],
                    'dose' => $requestData['dose'][$key],
                    'route' => $requestData['route'][$key],
                    'amount' => $requestData['amount'][$key],
                ]);
            }
        }
    } else {
        VisitMedicine::create($requestData);
    }

    flash('VisitMedicine added!', 'success');

    return redirect()->route('visit.edit', ['visit' => $requestData['visit_id'], 'page' => $requestData['page']])->with('success', 'VisitMedicine added successfully.');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $visitmedicine = VisitMedicine::findOrFail($id);

        return view('visit-medicine.show', compact('visitmedicine'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $visitmedicine = VisitMedicine::findOrFail($id);

        return view('visit-medicine.edit', compact('visitmedicine'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
public function update(Request $request, $id)
{
    $requestData = $request->all();

    $visitMedicine = VisitMedicine::findOrFail($id);
    $visitMedicine->update($requestData);

    return redirect()->route('visit.edit', ['visit' => $visitMedicine->visit_id, 'page' => $requestData['page']])
                    ->with('success', 'VisitMedicine updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
 public function destroy(Request $request, $id)
{
    $visitMedicine = VisitMedicine::findOrFail($id);
    $visitId = $visitMedicine->visit_id;

    VisitMedicine::destroy($id);

    return redirect()->route('visit.edit', ['visit' => $visitId, 'page' => $request->page])->with('success', 'VisitMedicine deleted successfully.');
}
}
