
<!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="utf-8">
      <title>Sidebar</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link
          href="https://fonts.googleapis.com/css2?family=Poppins&display=swap"
          rel="stylesheet"
      >
      <style>
        
:root {
    --primary-color: #008000;
    --text: #EDF0F7;
    --sidebar-gray: #45a049;
    --sidebar-gray-light: #F8F7FD;
    --sidebar-gray-background: #45a049;
    --success: #00C896;
    --white: #fff;
}

html {
  font-family: Poppins, sans-serif;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

nav {
    position: sticky;
    top: 0;
    left: 0;
    height: 100vh;
    background-color: var(--primary-color);
    width: 18rem;
    padding: 0.25rem 0.75rem;
    display: flex;
    color: var(--white);
    flex-direction: column;
    transition: width 0.5s linear;
}

main {
  flex: 1;
  display: flex;
  flex-direction: column;
}

body.collapsed nav {
  width: 5rem;
}

body.collapsed .hide {
  position: absolute;
  display: none;
  pointer-events: none;
}

/*? sidebar top */
.sidebar-top {
  position: relative;
  display: flex;
  align-items: start;
  justify-content: center;
  flex-direction: column;
  min-height: 2.5rem;
  padding: 1rem 0;
}

body.collapsed .sidebar-top {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.logo__wrapper {
  display: flex;
  justify-content: start;
  align-items: center;
  gap: 1.25rem;
  color: var(--white);
  text-decoration: none;
}

.logo {
  width: 3.5rem;
  height: 3.5rem;
  background: white;
  border-radius: 0.75rem;
}

.expand-btn {
  top: 1rem;
  right: -4.75rem;
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
  width: 3rem;
  height: 3rem;
  background: var(--white);
  cursor: pointer;
  box-shadow: #6067EB50 0px 2px 8px 0px;
}

.expand-btn img {
  transform: rotate(180deg);
  stroke: var(--primary-color);
  width: 2.375rem;
  height: 2.375rem;
}


body.collapsed .expand-btn img {
  transform: rotate(360deg);
}

.sidebar-links {
  padding: 0.5rem 0;
  border-top: 1px solid var(--sidebar-gray-background);
}

/*? menu links */
.sidebar-links ul {
  list-style-type: none;
  position: relative;
}

.sidebar-links li {
  position: relative;
}

.sidebar-links li a {
  padding: 0.875rem 0.675rem;
  margin: 0.5rem 0;
  color: var(--sidebar-gray-light);
  font-size: 1.25rem;
  display: flex;
  justify-content: start;
  align-items: center;
  border-radius: 0.675rem;
  height: 3.5rem;
  text-decoration: none;
  transition: all 0.2s ease-in-out;
}

.sidebar-links li a img {
  height: 2.125rem;
  width: 2.125rem;
}


.sidebar-links .link {
  margin-left: 1.875rem;
}

.sidebar-links li a:hover, 
.sidebar-links li a:focus, 
.sidebar-links .active {
  width: 100%;
  text-decoration: none;
  background-color: var(--sidebar-gray-background);
  border-radius: 0.675rem;
  outline: none;
  color: var(--sidebar-gray-light);
}

.sidebar-links .active {
  color: var(--white);
}

/*? bottom sidebar */

.sidebar-bottom {
  padding: 0.5rem 0;
  display: flex;
  justify-content: center;
  flex-direction: column;
  margin-top: auto;
}

/*? account part */
.sidebar__profile {
  display: flex;
  align-items: center;
  gap: 1.125rem;
  flex-direction: row;
  padding: 1.5rem 0.125rem;
  border-top: 1px solid var(--sidebar-gray-background);
}

.avatar__wrapper {
  position: relative;
  display: flex;
}

.avatar {
  display: block;
  width: 3.125rem;
  height: 3.125rem;
  cursor: pointer;
  border-radius: 50%;
  object-fit: cover;
  filter: drop-shadow(
    -20px 0 10px rgba(0, 0, 0, 0.1)
  );
}

.avatar:hover {
  transform: scale(1.05);
  transition: all 0.2s ease-in-out;
}

.avatar__name {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.user-name {
  font-size: 0.95rem;
  font-weight: 800;
  text-align: left;
}

.email {
  font-size: 0.9rem;
}

.online__status {
  position: absolute;
  width: 0.75rem;
  height: 0.75rem;
  border-radius: 50%;
  background-color: var(--success);
  bottom: 0.1875rem;
  right: 0.1875rem;
}

/* * Tooltip */
.tooltip {
  position: relative;
}

.tooltip .tooltip__content {
  visibility: hidden;
  background-color: var(--sidebar-gray-background);
  color: var(--white);
  text-align: center;
  border-radius: 0.375rem;
  padding: 0.375rem 0.75rem;
  position: absolute;
  z-index: 1;
  left: 4.6875rem;
}

body.collapsed .tooltip:hover .tooltip__content,
body.collapsed .tooltip:focus .tooltip__content {
  visibility: visible;
}

      </style>
    </head>
    <body>
        <nav>
            <div class="sidebar-top">
              <a href="#" class="logo__wrapper">
                <img src="{{ asset('assets/ifsp_logo_itp.png') }}" class="logo" alt="IFSP">
                <h1 class="hide">IFSP</h1>
              </a>
              <div class="expand-btn">
                <img src="{{asset('assets/arrw_right.svg')}}" alt="Chevron">
              </div>
            </div>
            <div class="sidebar-links">
                <ul>
                  <li>
                    <a href="#dashboard" title="Dashboard" class="tooltip">
                      <img src="{{asset('assets/review.svg')}}" alt="Dashboard">
                      <span class="link hide">Avaliações</span>
                      <span class="tooltip__content">Avaliações</span>
                    </a>
                  </li>
                  <li>
                    <a href="#project" title="Project" class="tooltip">
                        <img src="{{asset('assets/review.svg')}}" alt="Dashboard">
                      <span class="link hide">Críticas</span>
                      <span class="tooltip__content">Críticas</span>
                    </a>
                  </li>
                  <li>
                    <a href="#performance" title="Performance" class="tooltip">
                        <img src="{{asset('assets/review.svg')}}" alt="Dashboard">
                      <span class="link hide">Performance</span>
                      <span class="tooltip__content">Performance</span>
                    </a>
                  </li>
                </ul>
              </div>
              <div class="sidebar-bottom">
                <div class="sidebar-links">
                  <ul>
                    <li>
                      <a href="#help" title="Help" class="tooltip">
                       <img src="{{ asset('assets/help.svg') }}" alt="Help">
                        <span class="link hide">Ajuda</span>
                        <span class="tooltip__content">Ajuda</span>
                      </a>
                    </li>
                    <li>
                      <a href="#settings" title="Settings" class="tooltip">
                        <img src="{{ asset('assets/config.svg') }}" alt="Settings">
                        <span class="link hide">Configurações</span>
                        <span class="tooltip__content">Configurações</span>
                      </a>
                    </li>
                    <li>
                        <a href="#funds" title="Funds" class="tooltip">
                          <img src="{{ asset('assets/account.svg') }}" alt="Funds">
                          <span class="link hide">Conta</span>
                          <span class="tooltip__content">Conta</span>
                        </a>
                      </li>
                  </ul>
                </div>
                <div class="sidebar__profile">
                  <div class="avatar__wrapper">
                    <img class="avatar" src="{{ asset('assets/admin_panel.svg') }}" alt="Profile">
                    <div class="online__status"></div>
                  </div>
                  <div class="avatar__name hide">
                      <div class="user-name">{{Auth::user()->name}}</div>
                      <div class="email">{{Auth::user()->email}}</div>
                  </div>
                </div>
                {{-- <p>LOgout</p> --}}
              </div>
          </nav>
          <script src="script.js"></script>
    </body>
</html>
<script>
    const expand_btn = document.querySelector(".expand-btn");

let activeIndex;

expand_btn.addEventListener("click", () => {
  document.body.classList.toggle("collapsed");
});

const current = window.location.href;

const allLinks = document.querySelectorAll(".sidebar-links a");

allLinks.forEach((elem) => {
  elem.addEventListener('click', function() {
    const hrefLinkClick = elem.href;

    allLinks.forEach((link) => {
      if (link.href == hrefLinkClick){
        link.classList.add("active");
      } else {
        link.classList.remove('active');
      }
    });
  })
});

</script>