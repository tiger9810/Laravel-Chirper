<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //ChirpControllerのindexメソッド
    public function index(): View
    {
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // リクエストから取得したデータをバリデーションします。'message'フィールドは必須で、文字列でなければならず、最大255文字である必要があります。
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        // 現在認証されているユーザー（$request->user()）が所有するchirpsに、バリデーション済みのデータを使って新たなchirpを作成します。
        $request->user()->chirps()->create($validated);

        // 処理が終わったら、ユーザーをchirps.indexルート（つぶやき一覧画面）へリダイレクトします。
        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp): View
    {
        $this->authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp' =>$chirp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        $this->authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
        $chirp->update($validated);

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        //
    }
}
