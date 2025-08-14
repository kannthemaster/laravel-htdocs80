<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\ContactPerson;
use App\Models\Visit;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ContactPersonController extends Controller
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
            $contactperson = ContactPerson::where('date', 'LIKE', "%$keyword%")
                ->orWhere('name_surname', 'LIKE', "%$keyword%")
                ->orWhere('type', 'LIKE', "%$keyword%")
                ->orWhere('method', 'LIKE', "%$keyword%")
                ->orWhere('condom', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        }
        $cpsex = $request->get('cpsex');
        if (!empty($cpsex)) {
            $query = $query->where('cpsex', $cpsex);
        } else {
            $contactperson = ContactPerson::latest()->paginate($perPage);
        }

        return view('contact-person.index', compact('contactperson'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('contact-person.create');
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
        $requestData = ContactPerson::format2DB($requestData);
        ContactPerson::create($requestData);
        flash('ContactPerson added!','success');
        Alert::success('ContactPerson updated!');
        echo '<script type="text/javascript">'
               , 'history.go(-2);'
               , '</script>';
        // return Visit::next($requestData['page'], $requestData['visit_id']);
        // return redirect(route('visit.edit',$requestData['visit_id']))->with('flash_message', 'ContactPerson added!');
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
        $contactperson = ContactPerson::findOrFail($id);

        return view('contact-person.show', compact('contactperson'));
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
        $contactperson = ContactPerson::findOrFail($id);
        $contactperson->formatDBBack();
        return view('contact-person.edit', compact('contactperson'));
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
        
        $contactperson = ContactPerson::findOrFail($id);
        $contactperson->clearCheckBox();
        $requestData = ContactPerson::format2DB($requestData);
        $contactperson->update($requestData);
        flash('ContactPerson updated!','success');
        Alert::success('ContactPerson updated!');
        echo '<script type="text/javascript">'
               , 'history.go(-2);'
               , '</script>';
        // return redirect('backend/contact-person')->with('flash_message', 'ContactPerson updated!');
        // return redirect(route('visit.edit',$requestData['visit_id']))->with('flash_message', 'ContactPerson updated!');
        // return Visit::next($requestData['page'], $requestData['visit_id']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request,$id)
    {
        $requestData = $request->all();
        $contactperson = ContactPerson::findOrFail($id);
        $visit_id = $contactperson->visit_id;
        ContactPerson::destroy($id);
                flash('ContactPerson updated!','success');
        Alert::success('ContactPerson updated!');
        echo '<script type="text/javascript">'
               , 'history.go(-1);'
               , '</script>';
        // return redirect('backend/contact-person')->with('flash_message', 'ContactPerson deleted!');
        // return redirect(route('visit.edit',$visit_id))->with('flash_message', 'ContactPerson deleted!');
    }
}
