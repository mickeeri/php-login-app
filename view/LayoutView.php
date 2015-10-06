<?php

namespace view;



class LayoutView {  
  /**
   * Renders HTML
   * @param  boolean $isLoggedIn, Method from controller.
   * @param  LoginView $v, Sign up form
   * @param  DateTimeView $dtv       
   */
  public function render($isLoggedIn, $v, DateTimeView $dtv, NavigationView $nv) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 4</h1>
          ' . $this->renderNavigation($nv) . ' 
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          
          <div class="container">
              ' . $v->response() . '
              
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }
  
  /**
   * Renders h2 based on logged in or not.
   * @param  boolean $isLoggedIn
   */
  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }

    private function renderNavigation($nv) {
        if($nv->onLoginPage()){
            return $nv->getLinkToRegisterNewUser();
        } else {
            return $nv->getLinkToLogin();
        }
    }
}
