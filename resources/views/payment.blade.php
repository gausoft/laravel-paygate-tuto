@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <article class="card col-sm-8 mx-auto">
            @if(Session::has('success'))
            <div class="alert alert-success mt-2">
                {{ Session::get('success') }}
                @php
                Session::forget('success');
                @endphp
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-warning mt-2" uk-alert>
                {{ Session::get('error') }}
                @php
                Session::forget('error');
                @endphp
            </div>
            @endif
            <div class="card-body p-4">

                <ul class="nav bg-light nav-pills rounded nav-fill mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#nav-tab-card">
                            <i class="fa fa-credit-card"></i> Paiement</a></li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#nav-tab-paypal">
                            <i class="fab fa-paypal"></i> Paygate</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="nav-tab-card">
                        <!-- <p class="alert alert-success">Some text success or error</p> -->
                        <form action="" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="cardNumber">Numéro de téléphone</label>
                                <div class="input-group">
                                    <input type="tel" class="form-control @error('phoneNumber') is-invalid @enderror" name="phoneNumber" placeholder="Numéro de téléphone" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text text-muted">
                                            <img src="{{asset('flooz_logo.png')}}" width="40" alt="">
                                        </span>
                                    </div>
                                </div>
                            </div> <!-- form-group.// -->
                            <button class="subscribe btn btn-primary btn-block"> Confirmer </button>
                        </form>
                    </div> <!-- tab-pane.// -->
                    <div class="tab-pane fade" id="nav-tab-paypal">
                        <p>Aller vers le portail de paiement paygate</p>
                        <p>
                            <button type="button" class="btn btn-primary"> <i class="fab fa-paypal"></i> Via paygate </button>
                        </p>
                        <p><strong>Note:</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. </p>
                    </div>
                    <div class="tab-pane fade" id="nav-tab-bank">
                        <p>Bank accaunt details</p>
                        <dl class="param">
                            <dt>BANK: </dt>
                            <dd> THE WORLD BANK</dd>
                        </dl>
                        <dl class="param">
                            <dt>Accaunt number: </dt>
                            <dd> 12345678912345</dd>
                        </dl>
                        <dl class="param">
                            <dt>IBAN: </dt>
                            <dd> 123456789</dd>
                        </dl>
                        <p><strong>Note:</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. </p>
                    </div> <!-- tab-pane.// -->
                </div> <!-- tab-content .// -->

            </div> <!-- card-body.// -->
        </article>
    </div>
</div>
@endsection