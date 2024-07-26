<?php

namespace App\Http\Controllers; // Make sure this matches your file structure

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB facade
use Illuminate\Database\QueryException;
use Exception;
use App\Models\BanerImage;

class ProductController extends Controller
{
    public function product(Request $req)
    {
        $validatedData = $req->validate([
            "name" => 'required|string|max:50',
            "size" => 'required|numeric',
            'color' => 'required|string',
        ]);

        try {
            DB::table('product_data')->insert([
                "name" => $validatedData['name'],
                "size" => $validatedData['size'],
                "color" => $validatedData['color'],
                "created_at" => now(),
                "updated_at" => now()
            ]);

            return redirect()->back()->with('success', 'Successfully Value Inserted');
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    public function showPage()
    {
        
        return view('productAdd');
    }

    public function imageform(){
        $record = BanerImage::findOrFail(2);

        // Decode the JSON data
        $titles = json_decode($record->titleH, true) ?: [];
        $descriptions = json_decode($record->desH, true) ?: [];
        $images = json_decode($record->img, true) ?: [];

        // Pass the data to the view
        return view('baner', compact('titles', 'descriptions', 'images'));
        // return view('baner');
    }

    public function addBaner(Request $request){
        // print_r($request->all());
        // exit;
        $request->validate([
            'logo'=>'required',
        ]);

        $file=$request->file('logo');
        $filename=time().'_'.$file->getClientOriginalName();
        // $filename = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('logo'),$filename);

        $banerImage = BanerImage::firstOrCreate(
            ['name' => 'default'], 
            ['logo' => []] 
        );
        $logos = $banerImage->logo;
        $logos[] = $filename;
        $banerImage->logo = $logos;

        $banerImage->save();

        return back()->with('success', 'Image uploaded successfully.');
    }
    public function addFeature(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'titleH' => 'required|string|max:255',
            'desH' => 'required|string',
            'img' => 'required|image|max:2048', // validate image
        ]);

        // Retrieve the existing record with id = 2
        $record = BanerImage::findOrFail(2);

        // Decode the existing data, or initialize empty arrays if none exists
        $existingTitles = json_decode($record->titleH, true) ?: [];
        $existingDescriptions = json_decode($record->desH, true) ?: [];
        $existingImages = json_decode($record->img, true) ?: [];

        // Add the new data to the existing arrays
        $existingTitles[] = $validated['titleH'];
        $existingDescriptions[] = $validated['desH'];
        $existingImages[] = $validated['img']->store('images', 'public'); // store image and get path

        // Encode the data back to JSON
        $record->titleH = json_encode($existingTitles);
        $record->desH = json_encode($existingDescriptions);
        $record->img = json_encode($existingImages);

        // Save the record
        $record->save();

        return response()->json(['success' => 'Data saved successfully!']);
    }
    public function editData()
    {
        // Retrieve the existing record with id = 2
        $record = BanerImage::findOrFail(2);

        // Decode the JSON data
        $titles = json_decode($record->titleH, true) ?: [];
        $descriptions = json_decode($record->desH, true) ?: [];
        $images = json_decode($record->img, true) ?: [];

        // Pass the data to the view
        return view('your_edit_view_name', compact('titles', 'descriptions', 'images', 'record'));
    }

    public function updateData(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'titleH' => 'required|array',
            'desH' => 'required|array',
            'img' => 'sometimes|array',
            'img.*' => 'image|max:2048', // validate each image
        ]);

        // Retrieve the existing record with id = 2
        $record = BanerImage::findOrFail($id);

        // Decode the existing data, or initialize empty arrays if none exists
        $existingTitles = json_decode($record->titleH, true) ?: [];
        $existingDescriptions = json_decode($record->desH, true) ?: [];
        $existingImages = json_decode($record->img, true) ?: [];

        // Update existing data
        foreach ($validated['titleH'] as $index => $title) {
            $existingTitles[$index] = $title;
        }
        foreach ($validated['desH'] as $index => $description) {
            $existingDescriptions[$index] = $description;
        }
        if ($request->has('img')) {
            foreach ($request->file('img') as $index => $image) {
                $existingImages[$index] = $image->store('images', 'public');
            }
        }

        // Encode the data back to JSON
        $record->titleH = json_encode($existingTitles);
        $record->desH = json_encode($existingDescriptions);
        $record->img = json_encode($existingImages);

        // Save the record
        $record->save();

        return redirect()->back()->with('success', 'Data updated successfully!');
    }
        
    
}

    
    

