  
  <nav class="navbar navbar-expand navbar-light navbar-bg" >
  
  <i class="fa-solid fa-bars menu-btn rounded pointer" id="sidebarCollapse"></i>
  <div class="mt-1"><h5>Departamento Sistemas</h5></div>  
  <div class="navbar-collapse collapse">

    <div class="dropdown-divider"></div>

    <ul class="navbar-nav navbar-align">

    <li class="nav-item dropdown">
      <a class=" dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
        <i class="align-middle" data-feather="settings"></i>
      </a>
      <a class="nav-link dropdown-toggle d-none d-sm-inline-block pointer" data-bs-toggle="dropdown">
        <img src="<?=RUTA_IMG_ICONOS."usuario.png";?>" class="avatar img-fluid rounded-circle"/>
        <span class="text-dark" style="padding-left: 10px;">
          <?=$Session_TipoPuestoBD;?>  
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-end">
  
        <div class="user-box">

          <div class="u-text">
            <p class="text-muted">Nombre de usuario:</p>
            <h4><?=$Session_UsuarioNombre;?> </h4>
          </div>
        </div>
        <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?=HTTP?><?=HOST?>/departamento-operativo/perfil">
            <i class="fa-solid fa-user" style="padding-right: 5px;"></i>Perfil
          </a>
          <a class="dropdown-item pointer" onclick="tokenTelegram(<?= $Session_IDUsuarioBD ?>)">
                  <i class="fa-brands fa-telegram" style="padding-right: 5px;"></i>Token Telegram
                </a>
          <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?=RUTA_SALIR?>salir">
              <i class="fa-solid fa-power-off" style="padding-right: 5px;"></i> Cerrar Sesión
            </a>
      </div>
    </li>
  
  </ul>
  </div>
  </nav>