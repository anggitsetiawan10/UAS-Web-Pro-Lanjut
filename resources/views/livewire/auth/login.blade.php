    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">Pendataan Bantuan</span>
                </a>
              </div>

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Masuk ke Akun Kamu</h5>
                    <p class="text-center small">masukan username & password untuk login</p>
                  </div>

                    <!-- Alert Error -->
                  @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      {{ session('error') }}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                  @endif
                  <!-- End Alert -->

                  <form wire:submit="login" class="row g-3 needs-validation" novalidate="">

                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input wire:model="email" type="text" name="username" class="form-control @error('email') is-invalid @enderror" id="yourUsername" required="">
                        <div class="invalid-feedback">
                          @error('email') {{ $message }} @enderror
                        </div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input wire:model="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="yourPassword" required="">
                      <div class="invalid-feedback">
                        @error('password') {{ $message }} @enderror
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-outline-primary w-100" type="submit">Login</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
