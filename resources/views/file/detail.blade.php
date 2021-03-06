@extends('app.app')

@section('css')
    {{-- Additional CSS File for this page --}}
@endsection

@section('content')
    {{-- <div class="d-sm-flex align-items-center justify-content-between">
        <button onclick="toDestination('{{ $fileDetail->path }}')" class="btn btn-success btn-icon-split btn-sm">
            <span class="icon text-white-50">
                <i class="fas fa-chevron-circle-left"></i>
            </span>
            <span class="text">Kembali</span>
        </button>
    </div> --}}
    <div class="alert alert-danger" role="alert">
        {{-- A simple danger alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like. --}}
        <i class="fas fa-info-circle"></i> Halaman ini diperuntukan untuk preview saja. <b>Anda tidak diizinkan</b> untuk melakukan editing dokumen secara langsung pada halaman ini. 
    </div>
    <div class="card text-center mb-5">
        <div class="card-header d-sm-flex align-items-center justify-content-between">
            <button onclick="toDestination('{{ $fileDetail->path }}')" class="btn btn-success btn-icon-split btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-chevron-circle-left"></i>
                </span>
                <span class="text">Kembali</span>
            </button>
            <h6 class="card-title">{{ $fileDetail->nama_file }}</h6>
        </div>
        <div class="card-body">
            @if (ContentType::renderActionButton($fileDetail->path))
                @can('update')
                    <div id="keteranganActionButton">
                        <button class="btn btn-info btn-icon-split btn-sm btn-block mb-2 action-button" id="btnEditKeterangan" onclick="renderSaveButton()">
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span>
                            <span class="text">Edit Keterangan</span>
                        </button>
                    </div>
                @endcan
            @endif
            <textarea rows="3" disabled id="keterangan" name="keterangan" class="form-control" placeholder="Tidak ada keterangan">{!! $fileDetail->keterangan == null ? '' : $fileDetail->keterangan !!}</textarea>
            <hr>
            <embed src="{{ asset('storage'.ContentType::getContentByPath($fileDetail->path)) }}" height="600" width="100%"/>
            <hr>
        </div>
        <div class="card-footer text-muted">
            Size : {{ SizeConverter::formatSizeUnits($fileDetail->size) }} | Modified : {{ ContentType::modified($fileDetail->path) }} | Unit : {{ $fileDetail->unit->nama_unit }}
        </div>
    </div>
    <form action="{{ route('toDestination') }}" method="GET" style="display: none;" id="formToDestination">
        @csrf
        <input type="hidden" id="pathToDestination" name="path" value="">
    </form>

@endsection

@push('scripts')
    {{-- Additional JS File for this page --}}
    <script>
        function renderEditButton() {
            $('#keterangan').attr('disabled', 'disabled')
            $('#keteranganActionButton').html(`
                <button class="btn btn-info btn-icon-split btn-sm btn-block mb-2" id="btnEditKeterangan" onclick="renderSaveButton()">
                    <span class="icon text-white-50">
                        <i class="fas fa-edit"></i>
                    </span>
                    <span class="text">Edit Keterangan</span>
                </button>
            `)
        }

        function renderSaveButton() {
            $('#keterangan').removeAttr('disabled')
            $('#keteranganActionButton').html(`
                <div class="row">
                    <div class="col-md-10">
                        <button class="btn btn-warning btn-icon-split btn-sm btn-block mb-2 action-button" id="btnSimpanKeterangan" onclick="updateKeterangan('{{ $fileDetail->path }}')">
                            <span class="icon text-white-50">
                                <i class="fas fa-save"></i>
                            </span>
                            <span class="text">Simpan Keterangan</span>
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-danger btn-icon-split btn-sm btn-block mb-2 action-button" id="btnBatalKeterangan" onclick="renderEditButton()">
                            <span class="icon text-white-50">
                                <i class="fas fa-ban"></i>
                            </span>
                            <span class="text">Batal</span>
                        </button>
                    </div>
                </div>
            `)
        }

        function toDestination(path) {
            $('#pathToDestination').val(path)
            $('#formToDestination').submit()
        }

        function updateKeterangan(path) {
            const keterangan = $('#keterangan').val()
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: "{{ route('file.keterangan.update') }}",
                method: 'POST',
                data: {
                    path: path,
                    keterangan: keterangan
                },
                success: function(response) {
                    $('#keterangan').val(response.data.keterangan)
                    renderEditButton()
                }
            })
        }
    </script>
@endpush