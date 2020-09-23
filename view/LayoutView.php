<?php


class LayoutView {

  public function render($isLoggedIn, $v, DateTimeView $dtv) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->renderRegisterLink() . '
          ' . $this->renderIsLoggedIn($isLoggedIn) . '

          <div class="container">
              ' . $v->response() . '

              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }

  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }

  private function renderRegisterLink() {
    if (isset($_GET['register']))
    {
      return '<a href="?">Back to login</a>';
    }
    else if(empty($_POST['LoginView::UserName']) && empty($_SESSION['UserName']) && empty($_POST['LoginView::Password']) && empty($_SESSION['Password'])) {
      return '<a href="?register">Register a new user</a>';
    }
  }
}
