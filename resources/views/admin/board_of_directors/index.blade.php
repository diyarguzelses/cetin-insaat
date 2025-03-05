@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="alert alert-primary mt-2">
            <h5>Yönetim Kurulu Yönetimi Hakkında</h5>
            <p>Bu panel, web sitesinde listelenecek yönetim kurulu üyelerinin eklenmesi, düzenlenmesi ve silinmesi için kullanılır.</p>
            <h6>Girdi Alanları:</h6>
            <ul>
                <li><strong>Adı:</strong> Üyenin adı, zorunludur.</li>
                <li><strong>Özgeçmiş:</strong> Üyeye ait kısa bilgi girilmelidir.</li>
                <li><strong>Resim:</strong> Üyenin görseli, opsiyoneldir.</li>
            </ul>
        </div>

        <div class="card mb-5">
            <!-- Kart Başlık -->
            <div class="card-header text-white d-flex justify-content-between"
                 style="background: linear-gradient(135deg, #1e3c72, #2a5298); border-radius: 10px 10px 0 0;">
                <span class="fw-bold fs-5">Yönetim Kurulu Üyeleri</span>
                <!-- Yeni Üye Ekle butonu -->
                <a href="{{ route('admin.board_of_directors.create') }}" class="btn btn-light text-primary fw-bold">
                    <i class="bi bi-plus-circle"></i> Yeni Üye Ekle
                </a>
            </div>

            <!-- Kart İçerik -->
            <div class="card-body mt-3">
                <table id="boardDirectorsTable" class="table table-bordered mt-3">
                    <thead>
                    <tr>
                        <th>Sıra</th>
                        <th>ID</th>
                        <th>Adı</th>
                        <th>Özgeçmiş</th>
                        <th>Resim</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <small class="text-muted">Satırları tutup sürükleyerek yönetim kurulu sıralamasını değiştirebilirsiniz.</small>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- jQuery UI Sortable CDN -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function(){
            let table = $('#boardDirectorsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.board_of_directors.data") }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}"
                },
                columns: [
                    { data: 'order', name: 'order' },
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    {
                        data: 'biography',
                        name: 'biography',
                        render: function(data) {
                            let div = document.createElement('div');
                            div.innerHTML = data;
                            let plainText = div.textContent || div.innerText || "";
                            return plainText.length > 50 ? plainText.substr(0, 50) + '...' : plainText;
                        }
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            let imageUrl = data ? `/uploads/board_of_directors/${data}` : '/images/default-placeholder.png';
                            return `<img src="${imageUrl}" class="img-thumbnail" width="50">`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.board_of_directors.edit', ['id' => '__ID__']) }}"
                   class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil"></i> Düzenle
                </a>
                <button class="btn btn-danger btn-sm delete-director" data-id="__ID__">
                    <i class="bi bi-trash"></i> Sil
                </button>
            </div>
        `.replace(/__ID__/g, data.id);
                        }
                    }
                ]
            });

            // Satırlara data-id ekleme
            table.on('draw.dt', function(){
                $('#boardDirectorsTable tbody tr').each(function(){
                    let data = table.row(this).data();
                    if(data && data.id){
                        $(this).attr('data-id', data.id);
                    }
                });
            });

            // jQuery UI Sortable - Satırları sürükleyerek sıralama
            $("#boardDirectorsTable tbody").sortable({
                helper: function(e, tr) {
                    tr.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return tr;
                },
                update: function(event, ui) {
                    let orders = [];
                    $("#boardDirectorsTable tbody tr").each(function(index) {
                        let directorId = $(this).attr('data-id');
                        if(directorId){
                            orders.push({ id: directorId, order: index + 1 });
                        }
                    });

                    if(orders.length === 0){
                        Swal.fire({
                            title: 'Uyarı',
                            text: 'Sıralanacak üye bulunamadı.',
                            icon: 'warning'
                        });
                        return;
                    }

                    $.ajax({
                        url: "{{ route('admin.board_of_directors.updateOrder') }}",
                        method: "POST",
                        data: JSON.stringify({
                            _token: "{{ csrf_token() }}",
                            orders: orders
                        }),
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function(response){
                            Swal.fire({
                                title: 'Başarılı',
                                text: response.message,
                                icon: 'success'
                            }).then(function(){
                                table.ajax.reload();
                            });
                        },
                        error: function(){
                            Swal.fire({
                                title: 'Hata',
                                text: 'Sıra güncellenirken hata oluştu.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });

            // Yönetim Kurulu Üyesi Silme İşlemi
            $(document).on('click', '.delete-director', function(){
                let directorId = $(this).data('id');
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu kişiyi silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if(result.isConfirmed){
                        $.ajax({
                            url: "{{ route('admin.board_of_directors.destroy', ['id' => '__ID__']) }}".replace('__ID__', directorId),
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response){
                                table.ajax.reload();
                                Swal.fire('Silindi!', response.message, 'success');
                            },
                            error: function(){
                                Swal.fire('Hata!', 'Bir hata oluştu, tekrar deneyin.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
