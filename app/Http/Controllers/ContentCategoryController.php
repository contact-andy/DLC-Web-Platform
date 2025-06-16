<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\ContentCategory;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class ContentCategoryController extends Controller
{
    public function __construct()
    {
        $roles = Role::all();
        view()->share('roles',$roles);
    }
    public function index()
    {
        $data = ContentCategory::orderBy('id','DESC')->get();
        return view('admin.content_category.index', compact('data'));
    }
    public function create()
    {
        return view('admin.content_category.create');
    }
    public function store(Request $request)
    {

        try{
            $request->validate([
                'title' => 'required', 'string', 'max:255',
                'title2' => 'required', 'string', 'max:255',
                'title3' => 'required', 'string', 'max:255',
                'title4' => 'required', 'string', 'max:255',
                'title5' => 'required', 'string', 'max:255',
                'title6' => 'required', 'string', 'max:255',
                'title7' => 'required', 'string', 'max:255',
                'title8' => 'required', 'string', 'max:255',
                'title9' => 'required', 'string', 'max:255',
                'title10' => 'required', 'string', 'max:255',
                'title11' => 'required', 'string', 'max:255',
                'photo' => 'required', 'string', 'max:255',
            ]);
    
            $content_category = ContentCategory::create([
                'title' => $request->title,
                'title2' => $request->title2,
                'title3' => $request->title3,
                'title4' => $request->title4,
                'title5' => $request->title5,
                'title6' => $request->title6,
                'title7' => $request->title7,
                'title8' => $request->title8,
                'title9' => $request->title9,
                'title10' => $request->title10,
                'title11' => $request->title11,
                'description' => $request->description,
            ]);

            $photo="";
            if ($request->file('photo')) {
                $photo = $content_category->id.'.'.$request->file('photo')->getClientOriginalExtension();  
                $request->file('photo')->move(public_path('images/category'), $photo);
            }
            $k=ContentCategory::where('id', $content_category->id)->update(['photo'=>$photo]);
            // echo "Photo:$photo";

            return redirect()->route('admin.content_category.index')->with('success','Content category created successfully.');
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            return redirect()->route('admin.content_category.index')->with('error','Error, please try again.');
        }
    }
    public function edit($id)
    {
        $content_category = ContentCategory::where('id',decrypt($id))->first();
        return view('admin.content_category.edit',compact('content_category'));
    }
    public function update(Request $request, ContentCategory $content_category)
    {
        try{

            $request->validate([
                'title' => ['required', 'string','max:255', Rule::unique('content_categories')->ignore($content_category->id)],
                'title2' => ['required', 'string','max:255', Rule::unique('content_categories')->ignore($content_category->id)],
                'title3' => ['required', 'string','max:255', Rule::unique('content_categories')->ignore($content_category->id)],
                'title4' => ['required', 'string','max:255', Rule::unique('content_categories')->ignore($content_category->id)],
                'title5' => ['required', 'string','max:255', Rule::unique('content_categories')->ignore($content_category->id)],
                'title6' => ['required', 'string','max:255', Rule::unique('content_categories')->ignore($content_category->id)],
                'title7' => ['required', 'string','max:255', Rule::unique('content_categories')->ignore($content_category->id)],
                'title8' => ['required', 'string','max:255', Rule::unique('content_categories')->ignore($content_category->id)],
                'title9' => ['required', 'string','max:255', Rule::unique('content_categories')->ignore($content_category->id)],
                'title10' => ['required', 'string','max:255', Rule::unique('content_categories')->ignore($content_category->id)],
                'title11' => ['required', 'string','max:255', Rule::unique('content_categories')->ignore($content_category->id)],
                'photo' => 'required', 'string', 'max:255',
            ]); 

            $content_category = ContentCategory::find($request->id);
            $content_category->title = $request->title;
            $content_category->title2 = $request->title2;
            $content_category->title3 = $request->title3;
            $content_category->title4 = $request->title4;
            $content_category->title5 = $request->title5;
            $content_category->title6 = $request->title6;
            $content_category->title7 = $request->title7;
            $content_category->title8 = $request->title8;
            $content_category->title9 = $request->title9;
            $content_category->title10 = $request->title10;
            $content_category->title11 = $request->title11;
            $content_category->description = $request->description;
            $content_category->save();

            $photo="";
            if ($request->file('photo')) {
                $photo = $content_category->id.'.'.$request->file('photo')->getClientOriginalExtension();  
                $request->file('photo')->move(public_path('images/category'), $photo);
            }
            $k=ContentCategory::where('id', $content_category->id)->update(['photo'=>$photo]);
            return redirect()->route('admin.content_category.index')->with('success','Content category updated successfully.');
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            return redirect()->route('admin.content_category.index')->with('error','Error, please try again.');
        }
    }
    public function destroy($id)
    {
        ContentCategory::where('id',decrypt($id))->delete();
        return redirect()->back()->with('success','Content category deleted successfully.');
    }
    

    public function getCategory()
    {
        $category = ContentCategory::orderBy('id','DESC')->get();
        return response()->json(['category' => $category], 201);
    }
}
