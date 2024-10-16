<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\MaterialInfo;
use App\Models\Artigo;
use App\Models\Location;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class AdmController extends Controller
{

    //Index
    public function index(){
        return view('adm.index');
    }

    //Sugestao
    public function indexSugestoes(Request $request)
    {
        $query = MaterialInfo::query();
        $search = $request->input('name');
        $search .= ' ' . ($request->input('artigo') ?? '');
        $search .= ' ' . ($request->input('status') ?? '');

    
    if ($request->filled('name')) {
        $query->whereHas('user', function ($userQuery) use ($request) {
            $userQuery->where('name', 'like', '%' . $request->input('name') . '%');
        });
    }

    if ($request->filled('artigo')) {
        $query->where('cause', 'like', '%' . $request->input('artigo') . '%');
    }

    if ($request->filled('status')) {
        $query->where('status', $request->input('status'));
    }

    $articles = $query->with('user')->get();
    
    return view('adm.sugestoes', ['articles'=>$articles, 'search'=>$search]);
    }

    public function showSugestoes($id) {
        $suggestion = MaterialInfo::findOrFail($id);
        return view('adm.showSugestoes', ['suggestion' => $suggestion]);

    }

    public function destroySugestoes($id) {
        MaterialInfo::findOrFail($id)->delete();
        return redirect('/adm/sugestoes');
    }

    public function indexConfirmar($id)
    {
        $sugestao = MaterialInfo::findOrFail($id);
        $sugestao->status = 'Confirmado';
        $sugestao->save();

        return redirect()->back()->with('success', 'Sugestão confirmada com sucesso!');
    }

    public function indexRecusar($id)
    {
        $sugestao = MaterialInfo::findOrFail($id);
        $sugestao->status = 'Recusado';
        $sugestao->save();

        return redirect()->back()->with('success', 'Sugestão recusada com sucesso!');
    }



    //Artigo
    public function indexArtigo(){
        $search = request('search');

        if($search) {
            $articles = Artigo::where([
                ['title', 'like', '%'.$search.'%']
            ])->with('user')->get();
        } else {
            $articles = Artigo::with('user')->get();
            $locations = Location::with('user')->get();
        }
        return view('createArtAdm.artigos', ['articles' => $articles, 'locations' => $locations, 'search'=>$search]);
    }


    public function createArtigo(){
        return view('createArtAdm.artigosCreate');
    }


    public function storeArtigo(Request $request){

        $article = new Artigo;

        $article->title = $request->title;
        $article->type = $request->type;
        $article->description = $request->description;
        $article->categories = $request->categories;
        $article->discard = $request->has('discard') ? $request->discard : null;

        if($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('img/articles'), $imageName);

            $article->image = $imageName;
        }

        $user = auth()->user();
        $article->user_id = $user->id;

        $article->save();

        return redirect('/createArtAdm/artigos');

    }

    public function destroyArtigo($id){

        Artigo::findOrFail($id)->delete();
        return redirect('/createArtAdm/artigos');
    }

    public function editArtigo($id) {
        $article = Artigo::findOrFail($id);

        return view('createArtAdm.artigosEdit', ['article' => $article]);
    }

    public function updateArtigo(Request $request, $id) {
        $data = $request->all();

        if($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('img/articles'), $imageName);

            $data['image'] = $imageName;
        }

        Artigo::findOrFail($request->id)->update($data);

        return redirect('/createArtAdm/artigos');
    }

    public function artigoAtivado($id)
{
    $article = Artigo::findOrFail($id);
    $article->status = 'Ativado';
    $article->save();

    return redirect()->back()->with('success', 'Artigo ativado com sucesso!');
}

public function artigoDesativado($id)
{
    $article = Artigo::findOrFail($id); 
    $article->status = 'Desativado';
    $article->save();

    return redirect()->back()->with('success', 'Artigo desativado com sucesso!');
}

//Locations
    public function createLocation(){
        $articles = Artigo::all();

        return view('createArtAdm.locationsCreate', ['articles' => $articles]);
    }

    public function storeLocation(Request $request)
    {
        $location = new Location();
        $location->nLocal = $request->nLocal;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;

        $user = auth()->user(); 
        $location->user_id = $user->id;

        $location->article_id = $request->article_id;

        $location->save();

        return redirect('/createArtAdm/artigos')->with('success', 'Location created successfully.');
    }

    public function destroyLocation($id){

        Location::findOrFail($id)->delete();
        return redirect('/createArtAdm/artigos');
    }

    public function editLocation($id) {
        $location = Location::findOrFail($id);

        return view('createArtAdm.locationsEdit', ['location' => $location]);
    }

    public function updateLocation(Request $request) {
        $data = $request->all();

        Location::findOrFail($request->id)->update($data);

        return redirect('/createArtAdm/artigos');
    }
    


//Usuario
    public function indexUser(){
    $adminRequests = Admin::all();
    
    return view('adm.usuarios', compact('adminRequests'));
}

    public function requestAdmin(Request $request)
    {
        $existingRequest = Admin::where('user_id', $request->user()->id)->first();

    if ($existingRequest) {
        return redirect()->back()->with('error', 'Você já fez uma solicitação anteriormente.');
    }
        
        Admin::create([
            'user_id' => $request->user()->id,
            'reason' => $request->reason,
        ]);

        return redirect('/create/usuario');
    }

    public function showRequest($id) {
        $adminRequest = Admin::findOrFail($id);
        return view('adm.showUsuario', compact('adminRequest'));
    }

    //Request e remoc. de adm
    public function respondRequest($id, $status)
    {
        $request = Admin::findOrFail($id);
        $request->status = $status;
        $request->save();

        if ($status == 'approved' && $id != 1) {
            $user = User::findOrFail($request->user_id);
            $user->adm = '1';
            $user->save();
        }

        return redirect()->back();
    }

    public function removeAdmin($id)
{
    $user = User::findOrFail($id);
    $user->adm = '0'; // Remover privilégios de administrador
    $user->save();

    $request = Admin::where('user_id', $id)->first();
    if ($request) {
        $request->status = 'rejected'; // Atualizar o status para "Não"
        $request->save();
    }

    return redirect()->back()->with('success', 'Privilégios de administrador removidos com sucesso!');
}

public function removeRequest($id)
{
    Admin::findOrFail($id)->delete(); // Remover solicitação de administração

    return redirect()->back()->with('success', 'Solicitação de administração removida com sucesso!');
}


}