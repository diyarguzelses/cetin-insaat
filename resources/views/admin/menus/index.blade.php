@extends('admin.layouts.app')

@section('content')
    <div class="alert alert-primary mt-2">
        <strong>Menü Yönetimi:</strong> Buradan sitenizin ana ve alt menülerini oluşturabilir, güncelleyebilir ve silebilirsiniz.
        <ul class="mb-0">
            <li>Yeni bir menü eklemek için <strong>"Yeni Menü Ekle"</strong> butonuna tıklayın.</li>
            <li>Menü oluştururken <strong>"Ana Menü"</strong> veya <strong>"Alt Menü"</strong> seçebilirsiniz.</li>
            <li>Menü bir sayfaya bağlanabilir veya özel bir URL belirlenebilir. <strong>İkisi birden seçilemez!</strong></li>
            <li>Menü durumu "Aktif" veya "Pasif" olarak ayarlanabilir.</li>
            <li>Mevcut menüleri düzenlemek için <strong>"Düzenle"</strong>, silmek için <strong>"Sil"</strong> butonlarını kullanabilirsiniz.</li>
        </ul>
    </div>
    <div class="alert alert-primary">
        <strong>Bilgi:</strong> Menü sırasını değiştirmek için satırları tutup sürükleyebilirsiniz.
    </div>
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between"  style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
            <span class="fw-bold fs-5">Menü Yönetimi</span>
            <button class="btn btn-light text-primary fw-bold" id="addMenuBtn">
                <i class="bi bi-plus-circle"></i> Yeni Menü Ekle
            </button>
        </div>
        <div class="card-body mt-3">
            <table id="menusTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>Sıra</th>
                    <th>ID</th>
                    <th>Menü Türü</th>
                    <th>Adı</th>
                    <th>Bağlı Sayfa / URL</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
    </div>

    <!-- Menü Ekle & Düzenle Modal -->
    <div class="modal fade" id="menuModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Menü Bilgileri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="menuForm">
                        @csrf
                        <input type="hidden" id="menu_id">

                        <div class="mb-3">
                            <label>Menü Türü</label>
                            <select class="form-control" name="menu_type" id="menu_type">
                                <option value="main">Ana Menü</option>
                                <option value="submenu">Alt Menü</option>
                            </select>
                        </div>

                        <div class="mb-3 d-none" id="parentMenuContainer">
                            <label>Üst Menü</label>
                            <select class="form-control" name="parent_id" id="parent_id">
                                <option value="">Seçiniz</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Menü Adı</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>

                        <div class="mb-3">
                            <label>Bağlı Sayfa</label>
                            <select class="form-control" name="page_id" id="page_id">
                                <option value="">Seçiniz</option>
                                @foreach($pages as $page)
                                    <option value="{{ $page->id }}">{{ $page->title }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Eğer yeni bir sayfa eklemek istiyorsanız, burada tasarımını oluşturduğunuz sayfayı seçiniz.</small>
                        </div>

                        <div class="mb-3">
                            <label>URL</label>
                            <select class="form-control" name="url" id="url">
                                <option value="">Seçiniz...</option>
                                <option value="/communication">İletişim</option>
                                <option value="/completedProjects">Tamamlanan Projeler</option>
                                <option value="/continuingProjects">Devam Eden Projeler</option>
                                <option value="/career">Kariyer</option>
                                <option value="/machinePark">Makine Parkı</option>
                                <option value="/news">Haberler</option>
                            </select>
                            <small class="text-muted">Eğer sayfanız mevcut tasarımı olan sayfalardan biriyse, buradan ilgili URL’yi seçiniz.</small>
                        </div>


                        <div class="mb-3">
                            <label>Durum</label>
                            <select class="form-control" name="is_active" id="is_active">
                                <option value="1">Aktif</option>
                                <option value="0">Pasif</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            // DataTable tanımlaması
            let table = $('#menusTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.menus.data') }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}"
                },
                columns: [
                    { data: 'order_number', name: 'order_number' },
                    { data: 'id', name: 'id' },
                    { data: 'menu_type', name: 'menu_type' },
                    { data: 'name', name: 'name' },
                    { data: 'linked_content', name: 'linked_content', orderable: false, searchable: false },
                    { data: 'is_active', name: 'is_active' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });

            // DataTable her çizildiğinde her satıra data-id attribute'u ekleyelim
            table.on('draw.dt', function() {
                $('#menusTable tbody tr').each(function() {
                    var data = table.row(this).data();
                    if (data && data.id) {
                        $(this).attr('data-id', data.id);
                    } else {
                        console.warn("Bu satırda id bulunamadı:", data);
                    }
                });
            });

            // jQuery UI Sortable: Menü sıralamasını etkinleştiriyoruz
            $("#menusTable tbody").sortable({
                helper: function(e, tr) {
                    // Her hücrenin genişliğini sabit tutuyoruz
                    tr.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return tr;
                },
                update: function(event, ui) {
                    let orders = [];
                    $("#menusTable tbody tr").each(function(index, element) {
                        let menuId = $(element).attr('data-id');
                        if (menuId) {
                            // index + 1 kullanarak sıralama 1'den başlasın
                            orders.push({ id: menuId, order: index + 1 });
                        }
                    });
                    console.log("Gönderilen orders:", orders);

                    // Eğer orders dizisi boşsa uyarı verelim
                    if (orders.length === 0) {
                        Swal.fire({
                            title: 'Uyarı',
                            text: 'Sıralanacak menü öğesi bulunamadı.',
                            icon: 'warning'
                        });
                        return;
                    }

                    // AJAX isteği: Veriyi JSON formatında gönderiyoruz
                    $.ajax({
                        url: "{{ route('admin.menus.updateOrder') }}",
                        method: "POST",
                        data: JSON.stringify({
                            _token: "{{ csrf_token() }}",
                            orders: orders
                        }),
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({
                                title: 'Başarılı',
                                text: response.message,
                                icon: 'success'
                            }).then(function() {
                                table.ajax.reload();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Hata',
                                text: 'Menü sırası güncellenirken bir hata oluştu.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });

            // -- Aşağıda diğer CRUD işlemleri için mevcut kodlar yer alıyor --

            // Menü Tipine Göre Alanları Göster/Gizle
            $('#menu_type').change(function () {
                if ($(this).val() === 'submenu') {
                    $('#parentMenuContainer').removeClass('d-none');
                } else {
                    $('#parentMenuContainer').addClass('d-none');
                    $('#parent_id').val('');
                }
            });

            // URL ve Bağlı Sayfa Kontrolü: Aynı anda ikisini seçmeyi engelle
            $('#url, #page_id').on('input change', function () {
                if ($(this).attr('id') === 'url' && $(this).val().trim() !== '') {
                    $('#page_id').val('');
                } else if ($(this).attr('id') === 'page_id' && $(this).val() !== '') {
                    $('#url').val('');
                }
            });

            // Yeni Menü Ekle Butonu
            $('#addMenuBtn').click(function () {
                $('#menuForm')[0].reset();
                $('#menu_id').val('');
                $('#menuModal').modal('show');
            });

            // Menü Düzenleme İşlemi
            $(document).on('click', '.edit-menu', function () {
                let menuId = $(this).data('id');
                $.get(`/FT23BA23DG12/menus/${menuId}/edit`, function (data) {
                    $('#menu_id').val(data.id);
                    $('#name').val(data.name);
                    $('#url').val(data.url);
                    $('#page_id').val(data.page_id);
                    $('#is_active').val(data.is_active ? 1 : 0);
                    $('#menu_type').val(data.parent_id ? 'submenu' : 'main').trigger('change');
                    $('#parent_id').val(data.parent_id);
                    $('#menuModal').modal('show');
                });
            });

            // Menü Kaydetme & Güncelleme İşlemi
            $('#menuForm').submit(function (e) {
                e.preventDefault();

                let menuId = $('#menu_id').val();
                let url = $('#url').val().trim();
                let pageId = $('#page_id').val();

                // Hem URL hem de bağlı sayfa seçilmişse uyarı verelim
                if (url !== '' && pageId !== '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Geçersiz Seçim!',
                        text: 'Lütfen ya URL ya da Bağlı Sayfa seçiniz. İkisini aynı anda seçemezsiniz.',
                        confirmButtonText: 'Tamam',
                    });
                    return;
                }

                let requestUrl = menuId ? `/FT23BA23DG12/menus/${menuId}` : '/FT23BA23DG12/menus';
                let method = menuId ? 'PUT' : 'POST';

                $.ajax({
                    url: requestUrl,
                    method: method,
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#menuModal').modal('hide');
                        Swal.fire('Başarılı', response.message, 'success').then(function() {
                            window.location.reload();
                        });
                    },
                    error: function () {
                        Swal.fire('Hata', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });

            // Menü Silme İşlemi
            $(document).on('click', '.delete-menu', function () {
                let menuId = $(this).data('id');
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu menüyü silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/FT23BA23DG12/menus/${menuId}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire('Silindi!', response.message, 'success').then(function() {
                                    window.location.reload();
                                });
                            },
                            error: function () {
                                Swal.fire('Hata!', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
