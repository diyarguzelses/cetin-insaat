@extends('admin.layouts.app')

<style>
    /* İsteğe bağlı: Önizleme resmi için stil */
    #previewImage {
        display: none;
        margin-top: 10px;
        max-width: 100%;
        max-height: 200px;
    }
</style>

@section('content')
    <div class="container mt-4">

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Yönetim Kurulu Üyesini Düzenle</h3>
            </div>
            <br>
            <div class="card-body">
                <form id="directorEditForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Adı</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $director->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="biography" class="form-label">Özgeçmiş</label>
                        <textarea name="biography" id="biography" class="form-control" rows="6">{{ $director->biography }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Resim</label>
                        @if($director->image)
                            <div class="mb-2" id="currentImageContainer">
                                <img src="{{ asset('uploads/board_of_directors/' . $director->image) }}" alt="Üye Resmi" width="100">
                                <button type="button" id="deleteDirectorImageBtn" data-id="{{ $director->id }}" class="btn btn-danger btn-sm ms-2">
                                    Resmi Sil
                                </button>
                            </div>
                        @endif
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        <!-- Dosya seçildiğinde önizleme için eklenen img -->
                        <img id="previewImage" src="#" alt="Önizleme">
                    </div>

                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- CKEditor 5 Classic Editor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        // CKEditor için özel upload adapter tanımlama
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
            // Form gönderimi AJAX ile
            $('#directorEditForm').submit(function(e){
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.board_of_directors.update', $director->id) }}",
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

            // Mevcut resmi silme işlemi
            $('#deleteDirectorImageBtn').click(function(){
                let directorId = $(this).data('id');
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu resmi silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if(result.isConfirmed){
                        $.ajax({
                            url: `/admin/board-of-directors/delete-image/${directorId}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response){
                                if(response.success){
                                    Swal.fire({
                                        title: 'Silindi!',
                                        text: response.message,
                                        icon: 'success'
                                    });
                                    $('#currentImageContainer').remove();
                                } else {
                                    Swal.fire({
                                        title: 'Hata',
                                        text: response.message,
                                        icon: 'error'
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
                    }
                });
            });

            // Dosya seçildiğinde önizleme işlemi
            $('#image').change(function () {
                let file = this.files[0];
                if(file){
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewImage').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#previewImage').hide();
                }
            });
        });
    </script>
@endsection

