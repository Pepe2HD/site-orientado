<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Admin;
use App\Models\MaterialInfo;
use App\Models\Artigo;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function layout() {
        return view('layouts.layout');
    }

    public function index(){
        $articles = Artigo::all();

        return view('index.index', ['articles' => $articles]);
    }

    public function emailCheckup(){
        return view('index.emailChecked');
    }

    public function indexArtigo()
    {
        $search = request('search');
        $category = request('category');
    
        $articlesQuery = Artigo::query();
    
        if($search) {
            $articlesQuery->where('title', 'like', '%'.$search.'%');
        }
    
        if($category) {
            $articlesQuery->where('type', $category);
        }
    
        // Adicione a condição para o status Ativado
        $articlesQuery->where('status', 'Ativado');
    
        
        // Recupere todos os artigos junto com as localizações
    $articles = $articlesQuery->with('locations')->get();
    
    // Inicialize um array para armazenar todas as localizações
    $todasLocalizacoes = [];
    
    // Percorra cada artigo para recuperar suas localizações
    foreach ($articles as $article) {
        foreach ($article->locations as $location) {
            $todasLocalizacoes[] = $location;
        }
    }
    
        // Retorne a visualização da página de artigos com todas as localizações e dados de pesquisa
        return view('index.artigos', ['articles' => $articles, 'search' => $search, 'todasLocalizacoes' => $todasLocalizacoes ?: []]);
    }
    
    

    public function showArtigo($id) {
        $article = Artigo::findOrFail($id);
        $locations = $article->locations; 
        
        return view('index.showArtigo', ['article' => $article, 'locations' => $locations]);
    }
    

    public function indexUsuario(){
        $user = Auth::user();
        $articles = $user->suggestions; 
        $adminRequest = Admin::where('user_id', $user->id)->first();

        return view('create.usuario', ['user' => $user, 'articles' => $articles, 'adminRequest' => $adminRequest]);
    }

    public function solicitarAdmin(){
        return view('create.request_adm');
    }

    public function createSugestao(){
        return view('create.sugestao');
    }

    public function storeSugestao(Request $request){

        $sugestion = new MaterialInfo;
        $user = auth()->user();
        
        $sugestion->user_id = $user->id;
        $sugestion->cause = $request->cause;
        $sugestion->description = $request->description;

        $sugestion->save();

        //return redirect('/create/usuario');
    }

    public function showSugestao($id) {
        $suggestion = MaterialInfo::findOrFail($id);
        return view('create.showSugestao', ['suggestion' => $suggestion]);
    }

    public function destroySugestao($id){

        MaterialInfo::findOrFail($id)->delete();
        return redirect('/create/usuario');
    }

}
