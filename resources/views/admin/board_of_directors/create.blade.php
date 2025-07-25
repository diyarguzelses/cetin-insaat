@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Yeni Yönetim Kurulu Üyesi Ekle</h3>
            </div>
            <div class="card-body">
                <form id="directorCreateForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Adı</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="biography" class="form-label">Özgeçmiş</label>
                        <textarea name="biography" id="biography" class="form-control" rows="6"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Resim</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }

        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }
            upload() {
                return this.loader.file
                    .then(file => new Promise((resolve, reject) => {
                        const data = new FormData();
                        data.append('upload', file);
                        fetch('/api/ckeditor/upload', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: data
                        })
                            .then(response => response.json())
                            .then(result => {
                                resolve({ default: result.url });
                            })
                            .catch(error => {
                                reject('Dosya yüklenirken hata oluştu: ' + error);
                            });
                    }));
            }
            abort() {}
        }

        // CKEditor başlatma (textarea id'si "biography")
        ClassicEditor
            .create(document.querySelector('#biography'), {
                extraPlugins: [ MyCustomUploadAdapterPlugin ]
            })
            .then(editor => {
                window.directorEditor = editor;
                console.log('Editor custom adapter ile yüklendi.');
            })
            .catch(error => {
                console.error('CKEditor yüklenirken hata oluştu:', error);
            });
    </script>

    <script>
        $(document).ready(function(){
            $('#directorCreateForm').submit(function(e){
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.board_of_directors.store') }}",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        if(response.success){
                            Swal.fire({
                                title: 'Başarılı',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                window.location.href = "{{ route('admin.board_of_directors.index') }}";
                            });
                        }
                    },
                    error: function(){
                        Swal.fire({
                            title: 'Hata',
                            text: 'Bir hata oluştu, lütfen tekrar deneyin.',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
@endsection
