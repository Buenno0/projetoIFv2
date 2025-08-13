<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>@yield('title', 'Dashboard')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- AdminLTE + Bootstrap + Font Awesome (CDN) --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  {{-- Caso tenha CSS próprio --}}
  @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  {{-- Navbar --}}
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    {{-- Botão toggle sidebar --}}
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    {{-- Área direita do header (user, notificações, etc.) --}}
    <ul class="navbar-nav ml-auto">
      @includeWhen(View::exists('dashboard.includes.header'), 'dashboard.includes.header')
    </ul>
  </nav>

  {{-- Sidebar --}}
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
      <img src="{{ asset('assets/idea.svg') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity:.8">
      <span class="brand-text font-weight-light">Meu Painel</span>
    </a>
    <div class="sidebar">
      @includeWhen(View::exists('dashboard.includes.sidebar'), 'dashboard.includes.sidebar')
    </div>
  </aside>

  {{-- Content Wrapper --}}
  <div class="content-wrapper">
    {{-- Título / Breadcrumbs opcional --}}
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>@yield('page_title', 'Dashboard')</h1>
          </div>
          <div class="col-sm-6">
            @yield('breadcrumbs')
          </div>
        </div>
      </div>
    </section>

    {{-- Conteúdo principal --}}
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>
  </div>

  {{-- Footer --}}
  <footer class="main-footer">
    <strong>&copy; {{ date('Y') }} Meu Projeto.</strong> Todos os direitos reservados.
    <div class="float-right d-none d-sm-inline-block">
      <b>Versão</b> 1.0.0
    </div>
  </footer>

</div>

{{-- JS do AdminLTE/Bootstrap (CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>
