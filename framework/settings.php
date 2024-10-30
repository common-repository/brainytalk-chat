<?php
/** Cria a pasta de configuração do tema
 * @author Gabriel Stringari
 * @version 27/09/2015
 */
class ws_settings {
	//Cria a página de configuração do plugin
	function ws_settings(){
		$lConstants = new ws_constants();

		//Scripts
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'aes', plugin_dir_url( __FILE__ ).'admin/js/plugins/crypto/aes.js', false );
		wp_enqueue_script( 'app', plugin_dir_url( __FILE__ ).'admin/js/app/app.js?v=2', false );
		wp_enqueue_script( 'sweetalert', plugin_dir_url( __FILE__ ).'admin/js/plugins/sweetalert.min.js', false );
        wp_enqueue_script( 'jquerycomplexify', plugin_dir_url( __FILE__ ).'admin/js/plugins/complexify/jquery.complexify.js', false );
		wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ).'admin/js/plugins/bootstrap.min.js', false );
		wp_enqueue_script( 'masks-js', plugin_dir_url( __FILE__ ).'admin/js/plugins/jquery.mask.js', false );

        //Estilos
		wp_enqueue_style('font-awesome-admin', plugin_dir_url(__FILE__).'admin/font-awesome/css/font-awesome.css' , false);
        wp_enqueue_style('bootstrap-admin', plugin_dir_url(__FILE__).'admin/css/bootstrap.min.css' , false);
        wp_enqueue_style('admin-css', plugin_dir_url(__FILE__).'admin/css/admin.css' , false);
        wp_enqueue_style('sweetalert-css', plugin_dir_url(__FILE__).'admin/css/sweetalert.css' , false);

      if((get_option('brainytalk_chat_api_key') != NULL) && (get_option('brainytalk_chat_api_key') != "") && ((get_option('brainy_chat_widget_id') == NULL) || (trim(get_option('brainy_chat_widget_id')) == ''))) {
	
  	    $tipobotaochat = "B"; //Barra
	 	if(get_option('brainytalk_chat_style') == 'brainytalk-icon'){
	 		$tipobotaochat = "C"; //icon	 
	 	}
		// The data to send to the API
		$postData = array(
    			'Token' => get_option('brainytalk_chat_api_key'),
    			'Model' => array(				
						'Id' => '00000000-0000-0000-0000-000000000000',
	    				'Descricao' => "WordPress Chat",
	    				'CodEmpresa' => "00000000-0000-0000-0000-000000000000",
	  					'SiteLogado' => "WordpressNovo",
	    				'Tipo' => "W",
	    				'TituloChat' => get_option('brainytalk_chat_titulo'),
	    				'TipoBotao' => $tipobotaochat,
	    				'CorPrincipal' => get_option('brainy_chat_default_color'),
	    				'CorTexto' => get_option('brainy_chat_default_color_text'),
	    				'CorShadow' => get_option('brainy_chat_default_color_shadow'),
	    				'CorCabecalho' => get_option('brainy_chat_default_color_header'),
    					'ImagemFundoURL' => get_option('brainy_chat_back_image'),
	  					'Linguagem' => "pt-BR"
  				
					)
			);

			$ch = curl_init('https://ws.brainytalk.com/widget');
				curl_setopt_array($ch, array(    			
    				CURLOPT_RETURNTRANSFER => TRUE,
    				CURLOPT_HTTPHEADER => array(
	        			'Content-Type: application/json'
    			),
    			CURLOPT_POSTFIELDS => json_encode($postData)
			));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			// Send the request
			$response = curl_exec($ch);

			// Check for errors
			if($response === FALSE){
    			die(curl_error($ch));
			}

			// Decode the response
			$responseData = json_decode($response);
            
			 add_option('brainy_chat_widget_id', $responseData->{'Id'});
        } 


		
?>

<div class="wrap">
	<div class="row  border-bottom white-bg dashboard-header">
	 	<div class="col-sm-6">
    	 	<h2 class="logobrainy">
    	 		<span class="brainy">BRAINY</span><span class="talk">TALK</span>
    	 	</h2>
            <small><?=$lConstants::APP_NAME?></small>
	 	</div>
	 	<div class="col-sm-6" style="text-align: right;">
    	 	<img src="<?php echo plugin_dir_url(__FILE__) . 'admin/images/logo_ws.png'?>" alt="BrainyTalk" style="width: 65px;opacity: 0.2;"/>
	 	</div>
	</div>
 	<div class="row  border-bottom white-bg dashboard-header">
	 	<div class="col-sm-12">
	 		<div class="file-manager">
                <h5><?= __('Informações', 'brainytalk'); ?></h5>
                <ul class="category-list">
                	<li>
                		<i class="fa fa-circle text-navy"></i>
                		<?= __('Versão', 'brainytalk'); ?>: <b><?=$lConstants::APP_VERSION?></b>
                	</li>
                	<li>
                		<i class="fa fa-circle text-primary"></i>
                		<?= __('Site do Plugin', 'brainytalk'); ?>: <a href="<?=$lConstants::APP_SITE?>"><b>BrainyTalk Chat for WordPress</b></a>
                	</li>
                	<li>
                		<i class="fa fa-circle text-warning"></i>
                		<?= __('Sobre a BrainyTalk', 'brainytalk'); ?>: <a href="<?=$lConstants::DEV_SITE?>"><b>BrainyTalk.</b></a>
                	</li>
                </ul>
            </div>
	 	</div>
	</div>

	<div class="wrapper wrapper-content">
    	<div class="row">
    	<?php if(get_option('brainytalk_chat_api_key') == NULL){?>
    		<div class="col-lg-12" id="frminfouser">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?= __('Autenticação', 'brainytalk'); ?>
                            <small><?= __('Faça login ou crie uma conta', 'brainytalk'); ?></small>
                        </h5>
                        <div ibox-tools></div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-6 b-r"><h3 class="m-t-none m-b"><?= __('Entrar', 'brainytalk'); ?></h3>

                                <p><?= __('É necessário ter uma conta válida para utilizar o plugin.', 'brainytalk'); ?></p>
        						<form role="form" id="frmlogin">
                                    <div class="form-group">
                                    	<label><?= __('E-mail', 'brainytalk'); ?></label>
                                    	<input type="email" placeholder="<?= __('Digite seu e-mail', 'brainytalk'); ?>" class="form-control" id="email" required>
                                	</div>
                                    <div class="form-group">
                                    	<label><?= __('Senha', 'brainytalk'); ?></label>
                                    	<input type="password" placeholder="<?= __('Senha', 'brainytalk'); ?>" class="form-control" id="senha" required>
                                    </div>
                                    <div>
                                   <!--     <label><a href="#"><?= __('Esqueceu sua senha?', 'brainytalk'); ?></a></label>-->
                                    </div>
                                    <div class="right">
                                    	<button class="btn btn-primary" type="submit"><?= __('Entrar', 'brainytalk'); ?></button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-6"><h4><?= __('Não possui conta?', 'brainytalk'); ?></h4>

                                <p><?= __('Crie uma conta agora e começa a utilizar o plugin:', 'brainytalk'); ?></p>

                                <p class="text-center">
                                    <a href="" data-toggle="modal" data-target="#adduser"><i class="fa fa-sign-in big-icon"></i></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
    		</div>
		<?php }?>

    		<div class="col-lg-12" id="frmapikey" style="<?php if(get_option('brainytalk_chat_api_key') == NULL){?>display:none;<?php }?>">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?= __('Validação', 'brainytalk'); ?>
                            <small><?= __('Contas válidas possuem uma chave específica', 'brainytalk'); ?></small>
                        </h5>
                        <div ibox-tools></div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-12">
                            	<h3 class="m-t-none m-b"><?= __('Chave da API', 'brainytalk'); ?></h3>
                            	<p><?= __('API Key é um código único que utilizamos para identificar sua conta.', 'brainytalk'); ?></p>
                            	<p><?= __('Clique no botão abaixo para acessar o nosso aplicativo e responder aos visitantes em tempo real.', 'brainytalk'); ?></p>
                            	<form role="form">
                                    <div class="form-group">

                                        <input type="button" class="btn btn-primary" onclick="window.open('https://web.brainytalk.com', '_blank');" value="<?= __('Acessar Aplicação', 'brainytalk'); ?>"/>
                                    </div>
                                    <div class="form-group">
                                    	<label><?= __('API Key', 'brainytalk'); ?></label>
                                    	<input type="text" placeholder="<?= __('Digite sua API Key', 'brainytalk'); ?>" class="form-control" id="api_key" value="<?php echo get_option('brainytalk_chat_api_key'); ?>" required readonly>
                                	</div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
    		</div>            
    	</div>



    	<div class="row" id="rowconfig">
    		<div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>
                        	<?= __('Aparência', 'brainytalk'); ?>
                            <small><?= __('É hora de deixar o plugin com a sua cara', 'brainytalk'); ?></small>
                        </h5>
                        <div ibox-tools></div>
                    </div>
                    <div class="ibox-content">
					 	<div class="row">
                            <div class="col-sm-12">
								<form role="form" id="configLayout">
								<label><?= __('Selecione o canal de chat a ser usado', 'brainytalk'); ?></label>
                                    <div class="form-group">
										
                                		<select id="cboCanaisChat" class="col-sm-6">
										</select>
									</div>
								</form>
							</div>
						</div>
                        <br/><br/>
                        <div class="row">
                            <div class="col-sm-12">
                                <span>
                                    Visando melhorar a usabilidade e centralizar as configurações, as opções de personalização estarão disponíveis somente no painel da <a href="https://web.brainytalk.com">BrainyTalk</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
    		</div>
    	</div>
	</div>

	<form method="post" class="form-horizontal" action="options.php" name="formOptions" id="formOptions">
		<?php wp_nonce_field('update-options') ?>
		<input type="hidden" class="hidden" name="brainytalk_chat_api_key" id="brainytalk_chat_api_key" value="<?php echo get_option('brainytalk_chat_api_key'); ?>" />
		<input type="hidden" class="hidden" name="brainy_chat_widget_id" id="brainy_chat_widget_id" value="<?php echo get_option('brainy_chat_widget_id'); ?>"/>

    	<!-- Campos que fazem a gravação -->
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="brainytalk_chat_api_key,
														brainy_chat_widget_id" />

        <!-- Botões -->
        <div class="hr-line-dashed"></div>
        <div class="form-group">
            <div class="col-lg-12">
                <div class=center>
                    <button class="btn btn-sm btn-primary m-t-n-xs" type="submit" id="btnSaveChanges">
                    	<?= __('Salvar Alteraçoes', 'brainytalk'); ?>
                	</button>
                </div>
            </div>
        </div>
	</form>
</div>


<!-- Modal para criar novo usuário -->
<div class="modal inmodal" id="adduser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only"><?= __('Fechar', 'brainytalk'); ?></span></button>
                <i class="fa fa-laptop modal-icon"></i>
                <h4 class="modal-title"><?= __('Não possui uma conta?', 'brainytalk'); ?></h4>
                <small class="font-bold"><?= __('Crie sua conta agora, e aproveite o melhor do atendimento on-line.', 'brainytalk'); ?></small>
            </div>
            <div class="modal-body col-sm-12" >
            	<form method="POST" id="adicionauser">
                	<div class="row" style="margin-top: 10px;">
                        <div class="col-sm-12">
                            <label class="col-sm-4 control-label"><?= __('Nome Completo', 'brainytalk'); ?></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" placeholder="<?= __('João da Silva', 'brainytalk'); ?>" name="nomecompleto" id="nomecompleto"/>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-12">
                            <label class="col-sm-4 control-label"><?= __('Endereço de E-mail', 'brainytalk'); ?></label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" placeholder="<?= __('exemplo@servidor.com', 'brainytalk'); ?>" name="emailuser" id="emailuser"/>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-12">
                            <label class="col-sm-4 control-label"><?= __('Telefone', 'brainytalk'); ?></label>
                            <div class="col-sm-8">
                                <input type="phone" class="form-control" placeholder="(00) 0000-0000" name="telefone" id="telefone"/>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-12">
                            <label class="col-sm-4 control-label"><?= __('Senha', 'brainytalk'); ?></label>
                            <div class="col-sm-4">
                                <input type="password" class="form-control" placeholder="******" name="senhauser" id="senhauser"/>
                            </div>
                            <div class="col-sm-4">
                            	<input type="password" class="form-control" placeholder="******"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">

                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-12">
                            <label class="col-sm-4 control-label"><?= __('Força da senha', 'brainytalk'); ?></label>
                            <div class="col-sm-8">
                                <div class="progress progress-bar-default">
                                    <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-white" data-dismiss="modal"><?= __('Cancelar', 'brainytalk'); ?></button>
                    <button class="btn btn-primary" type="submit"><?= __('Salvar', 'brainytalk'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
}}?>