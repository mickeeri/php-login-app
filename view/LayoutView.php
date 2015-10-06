<?php

namespace view;



class LayoutView {  
  /**
   * Renders HTML
   * @param  boolean $isLoggedIn method from UserModel
   * @param  \view\ $v either LoginView or UserView
   * @param  DateTimeView $dtv       
   */
  public function render($isLoggedIn, $v, DateTimeView $dtv, AppView $nv) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 4</h1>
          ' . $this->renderNavigation($nv, $isLoggedIn) . ' 
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

    private function renderNavigation($nv, $isLoggedIn) {
        if($isLoggedIn === false) {
           if($nv->onLoginPage()){
               return $nv->getLinkToRegisterNewUser();
           } else {
               return $nv->getLinkToLogin();
           } 
       }
    }
}
