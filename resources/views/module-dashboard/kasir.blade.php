<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="callout callout-info">
                                    <small class="text-muted">Omset Hari ini</small>
                                    <br>
                                    <strong class="h4">Rp {{number_format($omsetHari)}}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="callout callout-dark">
                                    <small class="text-muted">Transaksi Hari ini</small>
                                    <br>
                                    <strong class="h4">{{$transHari}}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>