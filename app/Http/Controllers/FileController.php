<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreFileRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use Inertia\Inertia;
use Inertia\Response;


class FileController extends Controller
{
    // СТРАНИЦА СПИСКА ЗАПИСЕЙ
    public function index(Request $request)
    {
        $files = File::query()
            ->when($request->input('search'), function($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5); 

        return Inertia::render('Files/Index', [
            'files' => $files->map(function($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->name,
                    'extension' => $file->extension,
                    'size' => $file->size,
                    'image' => asset('storage/'. $file->image)
                ];
            }),
            'pagination' => $files->links()->toHtml(),
        ]);
    }


    // СТРАНИЦА СОЗДАНИЯ НОВОЙ ЗАПИСИ
    public function create()
    {
        return Inertia::render('Files/Create');
    }


    // СОХРАНЕНИЕ НОВОЙ ЗАПИСИ В БД
    public function store(StoreFileRequest $request)
    {
        // Сначала получает файл, который прошел валидацию в макс. 8мб в StoreFileRequest
        $validatedData = $request -> validated();

        $uploadedFile = $request->file('image');

        // Получает дополнительную информацию о загруженном файле:
        $extension = $uploadedFile->getClientOriginalExtension(); // расширение файла
        $sizeInMegabytes = $uploadedFile->getSize() / 1024 / 1024; // размер файла в мб
        $originalFilename = $uploadedFile->getClientOriginalName(); // оригинальное название файла  
        $originalFilename = pathinfo($originalFilename, PATHINFO_FILENAME); // название файла без расширения
        
        // Сохраняет файл в директории storage/app/public/files
        $image = $uploadedFile->store('files', 'public');

        // Если пользователь не ввел название файла, 
        // берем оригинальное название файла 
        if (empty($request->input('name'))) {
            $request->merge(['name' => $originalFilename]);
        }
        

        // Загружаем инфо в бд
        File::create([
            'name' => $request->input('name'),
            'image' => $image,
            'extension' => $extension,
            'size' => $sizeInMegabytes,
            'original_filename' => $originalFilename,
        ]);

    }

    // ОТОБРАЖЕНИЕ ЗАПИСИ
    public function show(string $id)
    {
        //
    }


    // ИЗМЕНЕНИЕ ЗАПИСИ
    public function edit(File $file)
    {
        return Inertia::render(
            'Files/Edit',
            [
                'file' => $file,
                'image' => asset('storage/' . $file->image)
            ]);
    }


    // ОБНОВЛЕНИЕ ЗАПИСИ В БД
    public function update(File $file, Request $request)
    {
        // Получает текущие значения полей
        $currentImage = $file->image;
        $currentName = $file->name;

        // Если новый файл был выбран
        if ($request->file('image')) {
            // Удаляет старый файл
            Storage::delete('public/' . $currentImage);

            // Сохраняет новый файл
            $currentImage = $request->file('image')->store('files', 'public');
        }

        // Обновляет название файла; если не предоставлено, берет оригинальное название файла
        $currentName = $request->filled('name') ? $request->input('name') : pathinfo($file->original_filename, PATHINFO_FILENAME);

        // Обновляет информацию
        $file->update([
            'name' => $currentName,
            'image' => $currentImage,
            'extension' => $request->file('image') ? $request->file('image')->getClientOriginalExtension() : $file->extension,
            'size' => $request->file('image') ? $request->file('image')->getSize() / 1024 / 1024 : $file->size,
            'original_filename' => $request->file('image') ? pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME) : $file->original_filename,
        ]);
    }

    // УДАЛЕНИЕ ЗАПИСИ
    public function destroy(string $id)
    {
        $file = File::findOrFail($id);

        // Удаляем файл из хранилища
        Storage::disk('public')->delete($file->image);

        // Удаляем запись из базы данных
        $file->delete();

        return redirect()->route('files.index');
    }


    // ГЕНЕРАЦИЯ ССЫЛОК ДЛЯ СКАЧИВАНИЯ ФАЙЛОВ 
    public function download(File $file)
    {
        $filePath = storage_path("app/public/{$file->image}");

        if (Storage::disk('public')->exists($file->image)) {
            return response()->download($filePath, $file->name . '.' . $file->extension); // генерирует название для файла 
        }

        abort(404, 'File not found');
    }
}
