<?php


class LayoutView {

  public function render($v, DateTimeView $dtv) {
    $showResp = $v->response();
    $LoginController = new LoginController();
    $isLoggedIn = $LoginController->checkIfLoggedIn();

    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->renderRegisterLink($isLoggedIn) . '
          ' . $this->renderIsLoggedIn($isLoggedIn) . '

          <div class="container">
              ' . $showResp . '

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

  private function renderRegisterLink($isLoggedIn) {
    if (isset($_GET['register']))
    {
      return '<a href="?">Back to login</a>';
    }
    else if(!$isLoggedIn) {
      return '<a href="?register">Register a new user</a>';
    }
  }
}
?>
