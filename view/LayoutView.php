<?php

namespace view;

class LayoutView {  
    /**
     * Renders page HTML
     * @param  boolean $isLoggedIn based on method in AppController
     * @param  \view\LoginView | \view\RegisterView $v 
     * @param  DateTimeView $dtv   
     * @param  AppView      $av 
     */
    public function render($isLoggedIn, $v, DateTimeView $dtv, AppView $av) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 4</h1>
          ' . $this->renderNavigation($av, $isLoggedIn) . ' 
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
   */
  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }

    // Render either link to login or link to register form. 
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
