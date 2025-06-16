<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\ContentCategory;
use App\Models\DigitalContent;
use App\Models\Language;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class DigitalContentController extends Controller
{
    public function __construct()
    {
        $roles = Role::all();
        view()->share('roles',$roles);
    }
    public function index()
    { 
        $data = DigitalContent::orderBy('id','DESC')->get();
        $contentCategoryData = ContentCategory::orderBy('id','DESC')->get();
        $languageData = Language::orderBy('id','ASC')->get();
        return view('admin.digital_content.index', [
            'data' => $data,
            'contentCategoryData' => $contentCategoryData,
            'languageData' => $languageData,
        ]);
    }
    public function create()
    {
        $contentCategoryData = ContentCategory::orderBy('id','DESC')->get();
        $languageData = Language::orderBy('id','ASC')->get();
        return view('admin.digital_content.create', [
            'contentCategoryData' => $contentCategoryData,
            'languageData' => $languageData,
        ]);
    }
    public function store(Request $request)
    {

        try{
            $request->validate([
                'title' => 'required', 'string', 'max:255',
                'video' => 'required',
                'poster' => 'required',
            ]);
    
            $digital_content = DigitalContent::create([
                'title' => $request->title,
                'description' => $request->description,
                'categoryId' => $request->contentCategory,
                'languageId' => $request->language,
            ]);
            
            $video="";
            if ($request->file('video')) {
                $video = $digital_content->id.'.'.$request->file('video')->getClientOriginalExtension();  
                $request->file('video')->move(storage_path('app/public/videos/'), $video);
            }

            $poster="";
            if ($request->file('poster')) {
                $poster = $digital_content->id.'.'.$request->file('poster')->getClientOriginalExtension();  
                $request->file('poster')->move(public_path('posters'), $poster);
            }

            $k=DigitalContent::where('id', $digital_content->id)->update(['fileName'=>$video,'poster'=>$poster]);
            // echo "Photo:$photo";

            return redirect()->route('admin.digital_content.index')->with('success','Digital content created successfully.');
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            return redirect()->route('admin.digital_content.index')->with('error','Error, please try again.');
        }
    }
    public function edit($id)
    {
        $contentCategoryData = ContentCategory::orderBy('id','DESC')->get();
        $languageData = Language::orderBy('id','ASC')->get();
        $digital_content = DigitalContent::where('id',decrypt($id))->first();
        return view('admin.digital_content.edit',[
            'digital_content' => $digital_content,
            'contentCategoryData' => $contentCategoryData,
            'languageData' => $languageData,
        ]);
    }
    public function update(Request $request, DigitalContent $digital_content)
    {
        try{

            $request->validate([
                'title' => ['required', 'string','max:255', Rule::unique('digital_contents')->ignore($digital_content->id)],
            ]); 

            $digital_content = DigitalContent::find($request->id);
            $digital_content->title = $request->title;
            $digital_content->description = $request->description;
            $digital_content->categoryId = $request->contentCategory;
            $digital_content->languageId = $request->language;
            $digital_content->save();
            
            $video="";
            if ($request->file('video')) {
                $video = $digital_content->id.'.'.$request->file('video')->getClientOriginalExtension();  
                $request->file('video')->move(public_path('videos'), $video);
            }

            $poster="";
            if ($request->file('poster')) {
                $poster = $digital_content->id.'.'.$request->file('poster')->getClientOriginalExtension();  
                $request->file('poster')->move(public_path('posters'), $poster);
            }

            if(strcmp($video,"")!=0){
                $k=DigitalContent::where('id', $digital_content->id)->update(['fileName'=>$video]);
            }
            if(strcmp($poster,"")!=0){
                $k=DigitalContent::where('id', $digital_content->id)->update(['poster'=>$poster]);
            }
            
            // echo "Photo:$photo";
            return redirect()->route('admin.digital_content.index')->with('success','Digital content updated successfully.');
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            return redirect()->route('admin.digital_content.index')->with('error','Error, please try again.');
        }
    }
    public function destroy($id)
    {
        DigitalContent::where('id',decrypt($id))->delete();
        return redirect()->back()->with('success','Digital content deleted successfully.');
    }
    public function view($id)
    {
        
        $contentCategoryData = ContentCategory::orderBy('id','DESC')->get();
        $languageData = Language::orderBy('id','ASC')->get();
        $digital_content = DigitalContent::where('id',decrypt($id))->first();
        return view('admin.digital_content.view',[
            'digital_content' => $digital_content,
            'contentCategoryData' => $contentCategoryData,
            'languageData' => $languageData,
        ]);
    }
    public function getContent($type, $languageId,$value,$page)
    {
        $value = trim($value);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        // `SELECT digital_contents.id,digital_contents.title,digital_contents.description,digital_contents.fileName,
        //digital_contents.poster,content_categories.title as categoryTitle,language.title as languageTitle FROM 
        //digital_contents INNER JOIN content_categories ON digital_contents.categoryId=content_categories.id 
        //INNER JOIN language ON digital_contents.languageId=language.id 
        //WHERE  digital_contents.languageId = ? and 
        //content_categories.title = ? ORDER BY digital_contents.id DESC limit ${limit} offset ${offset} `,
        if (strcmp($type,"cat")==0) {
            $content = DigitalContent::select('digital_contents.id','digital_contents.title','digital_contents.description',
            'digital_contents.fileName','digital_contents.poster','content_categories.title as categoryTitle',
            'languages.title as languageTitle')
            ->join('content_categories', 'content_categories.id', '=', 'digital_contents.categoryId')
            ->join('languages', 'languages.id', '=', 'digital_contents.languageId')
            ->where('digital_contents.languageId',$languageId)
            ->where('content_categories.title',$value)
            ->orderBy('digital_contents.id','DESC')
            ->skip($offset)->take($limit)->get();
            return response()->json(['content' => $content], 201);
        }
        else if (strcmp($type,"id")==0) {
            $content = DigitalContent::where('id',$value)->get();
            return response()->json(['content' => $content], 201);
        }
        else if (strcmp($type,"latest")==0) {
            $content = DigitalContent::take($limit)->get();
            return response()->json(['content' => $content], 201);
        }
        else{
            $content = DigitalContent::where('title', 'like', '%' . $value . '%')
            ->orderBy('digital_contents.id','DESC')
            ->skip($offset)->take($limit)->get();
            return response()->json(['content' => $content], 201);
        }
    }
}