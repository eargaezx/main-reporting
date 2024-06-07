<script>
    Application.angular.controller("user.profile", function (Webservices, Common, Auth, $timeout) {
        var self = this;
        this.common = Common;
        this.exist = true;

        this.$pageInit = function () {
            this.common = Common;
            this.form = Common.business;
        };

        this.logout = function () {
            Application.framework7.dialog.confirm("¿Realmente deseas cerrar tu sesión?", "Cerrar sesión", function () {

                localStorage.clear();
                Common.user = undefined;
                Application.framework7.router.navigate("/splash/", {
                    clearPreviousHistory: true
                });
            });
        };

        this.settings = function () {
            Application.framework7.router.navigate("/user-settings/");
        };

        this.wallet = function () {
            Application.framework7.router.navigate("/methods-payments/");
        };


        this.business = function () {
            Application.framework7.router.navigate("/business-ads/");
        };


        this.history = function () {
            Application.framework7.router.navigate("/order-history/");
        };

        this.about = function () {

            Application.framework7.router.navigate({
                name: 'api',
                params: {
                    controller: 'Customers',
                    action: 'about'
                }
            });
        };

        this.sendEmail = function (data) {
            Webservices.sendEmail(data).then(function () {
                Application.framework7.params.dialog.buttonOk = "Hecho";
                Application.framework7.dialog.alert("Mensaje enviado", "Epicentro");
            });
        };




        this.requestLogin = function (form) {

            $timeout(function () {
                Application.framework7.input.validateInputs('.cardcard-content-padding');
            });


            form.Account['phone_country'] = "+521";
            Webservices.play({data: form}).then(function (response) {
                if (response.success == true) {
                    Auth.updateProperity("waiting", "true");
                    Auth.updateProperity("username", form.Account.username);
                    self.exist = true;

                    Auth.updateProperity("waiting", "true");
                    Auth.updateProperity("username", form.Account.username);

                    self.waiting = Auth.getProperity("waiting");


                    Application.framework7.router.refreshPage();

                    Application.framework7.sheet.close();



                } else {
                    self.exist = false;

                    $timeout(function () {
                        Application.framework7.input.validateInputs('.cardcard-content-padding');
                    }, 300);


                    Webservices.signin({data: form}).then(function (response) {
                        if (response.success == true) {

                            Auth.updateProperity("waiting", "true");
                            Auth.updateProperity("username", form.Account.username);



                            /*Application.framework7.router.navigate("/login/", {
                             force: true,
                             ignoreCache: true
                             */
                            self.waiting = Auth.getProperity("waiting");

                        } else {
                            $timeout(function () {
                                Application.framework7.input.validateInputs('.cardcard-content-padding');
                            });

                            /*Application.framework7.params.dialog.buttonOk = "Hecho";
                             Application.framework7.dialog.alert(response.message, "Piddo");*/
                        }
                    });
                }
            });
        };

    });



    var sht = Application.framework7.sheet.create({
        swipeToClose: true,
        backdrop: true,
        content: `<div ng-controller="user.profile as $this" class="sheet-modal sheet-modal-top my-sheet-top" style="height: auto;">
                            <div class="toolbar toolbar-bottom" style="background-color:#f7f7f8">
                              <div class="toolbar-inner">
                                <div class="left"></div>
                                <div class="right"><a class="link sheet-close color-primary" href="#">Cerrar</a></div>
                              </div>
                            </div>
                            <div class="sheet-modal-inner">
        
        
                                 <div  ng-if="$this.common.user == undefined" class="list no-hairlines-md no-margin no-padding">

                                    <ul> 
        
                                        <li class="item">
                                            <div class="item item-content bg-primary sheet-close">
                                                <div class="item-media" style="min-width: 10px;">
                                                    <i class="material-icons" style="color: #fff; font-size: 40px;">account_circle</i>             
                                                </div>
                                                <div class="item-inner">
                                                    <div class="row text-size-12">
                                                        <div class="col-100 text-weight-bold text-size-15 text-color-white">
                                                            Ingresar
                                                        </div>
                                                        <div class="col-100 text-size-12 text-color-white">
                                                          Ingresa tu número de telefono para iniciar con tu cuenta.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
        
        
                                        <li  class="item-content item-input item-input-outline">
                                            <div class="item-media align-items-center" style="padding-top: 24px;">
                                                +52
                                            </div>
                                            <div class="item-inner" style="padding-left: 0px;">
                                                <div class="item-title item-floating-label">Teléfono</div>
                                                <div class="item-input-wrap">
                                                    <input ng-model="$this.common.order.Account.phone_number" class="input-lazy" type="tel" maxlength="10" required validate/>
                                                    <span class="input-clear-button"></span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>                    

                
                                    <div ng-if="$this.exist == false" class="row no-gap col-100" style="margin-top: -10px;">
                                            <div class="col-50">
                                                <ul>
                                                    <li class="item-content item-input item-input-outline" style="padding-right: 0px;">
                                                        <div class="item-inner">
                                                            <div class="item-title item-floating-label">Nombre</div>
                                                            <div class="item-input-wrap">
                                                                <input ng-model="$this.common.order.Account.first_name"  class="input-lazy-2" type="text" required validate/>
                                                                <span class="input-clear-button"></span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-50">
                                                <ul>
                                                    <li class="item-content item-input item-input-outline" style="padding-left: 0px;">
                                                        <div class="item-inner">
                                                            <div class="item-title item-floating-label">Apellido</div>
                                                            <div class="item-input-wrap">
                                                                <input ng-model="$this.common.order.Account.last_name" class="input-lazy-3" type="text" required validate/>
                                                                <span class="input-clear-button"></span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                  </div>
                                                                        
                                                                        
                                <div ng-if="$this.common.user == undefined" class="row no-gap col-100" style="margin-top:16px;" >
                                    <div class="col-5">  </div>                                      
                                     <div class="col-90">                                       
                                         <button ng-click="$this.requestLogin($this.common.order)" class="col button button-raised button-fill color-yellow bg-primary button-round">Ingresar</button>
                                    </div> 
                                     <div class="col-5">  </div>  
                                 </div>

        
            
                                 <div ng-if="$this.common.user != undefined"  class="list no-margin no-padding">
                                    <ul>
                                        <li class="item-link">
                                            <a  ng-click="$this.settings()"  class="item-link item-content bg-primary sheet-close">
                                                <div class="item-media" style="min-width: 10px;">
                                                    <i class="material-icons" style="color: #fff; font-size: 40px;">account_circle</i>             
                                                </div>
                                                <div class="item-inner">
                                                    <div class="row text-size-12">
                                                        <div class="col-100 text-weight-bold text-size-15 text-color-white">
                                                             {{$this.common.user.name}}
                                                        </div>
                                                        <div class="col-100 text-size-12 text-color-white">
                                                          {{$this.common.user.phone}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
            
                                       <!--<li>
                                          <a ng-click="$this.business()" class="item-content item-link sheet-close">
                                             <div class="item-media"><i class="material-icons color-primary">store</i></div>
                                             <div class="item-inner">
                                                <div class="item-title">Registrar negocio</div>
                                             </div>
                                          </a>
                                       </li>
            
                                     <li>
                                        <a ng-click="$this.history()" class="item-content item-link sheet-close">
                                             <div class="item-media"><i class="material-icons color-primary">history</i></div>
                                             <div class="item-inner">
                                                <div class="item-title">Mi historial</div>
                                             </div>
                                          </a>
                                       </li>
            
                                      <li>
                                        <a ng-click="$this.wallet()" class="item-content item-link sheet-close">
                                             <div class="item-media"><i class="material-icons color-primary">account_balance_wallet</i></div>
                                             <div class="item-inner">
                                                <div class="item-title">Métodos de pago</div>
                                             </div>
                                          </a>
                                     </li>-->
            
            
            
                                        <li>
                                          <a ng-click="$this.about()" class="item-content item-link sheet-close">
                                             <div class="item-media"><i class="material-icons color-primary">help_outline</i></div>
                                             <div class="item-inner">
                                                <div class="item-title">Acerca de</div>
                                             </div>
                                          </a>
                                       </li>
            
                                       <li>
                                          <a ng-click="$this.logout()" class="item-content item-link sheet-close">
                                             <div class="item-media"><i class="material-icons color-primary">power_settings_new</i></div>
                                             <div class="item-inner">
                                                <div class="item-title">Cerrar sesión</div>
                                             </div>
                                          </a>
                                       </li>
                                    </ul>
                                 </div>
                                 <div class="block"><h4></h4></div>
                                 <br>
                            </div>
                          </div>`,
        on: {
            open: function (sheet) {

            },
            opened: function (sheet) {

            },
        }
    });

    var $page = $(sht.el);
    var injector = angular.element("#application").injector();

    if (injector) {
        var $compile = injector.get("$compile");
        var $timeout = injector.get("$timeout");
        var $scope = injector.get("$rootScope");
        $scope = $scope.$$childHead;

        $compile($page)($scope);
        $timeout(function () {
            angular.element(page.el).scope().$broadcast(event);
        }, 100);
    }

    if (localStorage.getItem("session.waiting") == 'true') {
        Application.framework7.params.dialog.buttonOk = "Hecho";
        Application.framework7.dialog.alert("Ya casi terminas, te hemos enviado un SMS con la informacion para verrificar tu cuenta", "Mercadito Naranja");
    } else {
        sht.open();
    }




</script> 


