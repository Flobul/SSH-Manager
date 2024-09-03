<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$plugin = plugin::byId('sshmanager');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
    <!-- Page d'accueil du plugin -->
    <div class="col-xs-12 eqLogicThumbnailDisplay">
        <div class="row">
            <div class="col-sm-10">
                <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
                <!-- Boutons de gestion du plugin -->
                <div class="eqLogicThumbnailContainer">
                    <div class="cursor eqLogicAction logoPrimary" data-action="add">
                        <i class="fas fa-plus-circle"></i>
                        <br/>
                        <span style="color:var(--txt-color)">{{Ajouter}}</span>
			        </div>
			        <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				        <i class="fas fa-wrench"></i>
				        <br>
				        <span style="color:var(--txt-color)">{{Configuration}}</span>
			        </div>
                    <div class="cursor pluginAction logoSecondary" data-action="openLocation" data-location="<?= $plugin->getDocumentation() ?>">
				        <i class="fas fa-book icon_blue"></i>
				        <br>
				        <span style="color:var(--txt-color)">{{Documentation}}</span>
			        </div>
        			<div class="cursor pluginAction logoSecondary" data-action="openLocation" data-location="https://community.jeedom.com/tag/plugin-<?= $plugin->getId() ?>">
				        <i class="fas fa-thumbs-up icon_green"></i>
				        <br>
				        <span style="color:var(--txt-color)">{{Community}}</span>
			        </div>
		        </div>
            </div>
        </div>
        <legend><i class="fas fa-laptop-code"></i> {{Mes SSH(s)}}</legend>

		<?php
		if (count($eqLogics) == 0) {
			echo '<br><div class="text-center" style="font-size:1.2em;font-weight:bold;">{{Aucun équipement SSH-Manager trouvé, cliquer sur "Ajouter" pour commencer}}</div>';
		} else {
			// Champ de recherche
			echo '<div class="input-group" style="margin:5px;">';
			echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic">';
			echo '<div class="input-group-btn">';
			echo '<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
			echo '<a class="btn roundedRight hidden" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>';
			echo '</div>';
			echo '</div>';
			// Liste des équipements du plugin
			echo '<div class="eqLogicThumbnailContainer">';
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $eqLogic->getImage() . '"/>';
				echo '<br>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '<span class="hiddenAsCard displayTableRight hidden">';
				echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Equipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Equipement non visible}}"></i>';
				echo '</span>';
				echo '</div>';
			}
			echo '</div>';
		}
		?>
	</div> <!-- /.eqLogicThumbnailDisplay -->

    <!-- Page de présentation de l'équipement -->
	<div class="col-xs-12 eqLogic" style="display: none;">
        <!-- barre de gestion de l'équipement -->
		<div class="input-group pull-right" style="display:inline-flex">
            <span class="input-group-btn">
				<!-- Les balises <a></a> sont volontairement fermées à la ligne suivante pour éviter les espaces entre les boutons. Ne pas modifier -->
				<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
				</a><a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs"> {{Dupliquer}}</span>
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
				</a>
			</span>
		</div>
        <!-- Onglets -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-laptop-code"></i> {{SSH-Manager}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
            <!-- Onglet de configuration de l'équipement -->
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
                <div class="row">
					<div class="col-sm-6">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{Nom de l'équipement}}</label>
                                    <div class="col-md-6">
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement SSH-Manager}}" />
                                    </div>
                                </div>

								<div class="form-group">
									<label class="col-sm-4 control-label">{{Objet parent}}</label>
									<div class="col-sm-6">
										<select class="form-control eqLogicAttr" data-l1key="object_id">
											<option value="">{{Aucun}}</option>
											<?php $options = '';
											foreach ((jeeObject::buildTree(null, false)) as $object) {
												$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
											}
											echo $options;
											?>
										</select>
									</div>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{Catégorie}}</label>
                                    <div class="col-sm-8">
                                        <?php
                                            foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                                echo '<label class="checkbox-inline">';
                                                echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                                                echo '</label>';
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"></label>
                                    <div class="col-md-8">
                                        <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked />{{Activer}}</label>
                                        <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked />{{Visible}}</label>
                                    </div>
                                </div>
                                <br/>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{Password ou Clé ?}}</label>
                                    <div class="col-md-6">
                                        <select id="pwdorkey" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="pwdorkey">
                                            <option value="remote-pwd">{{Mot de Passe}}</option>
                                            <option value="remote-key">{{Clé SSH}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{{Adresse IP}}</label>
                                        <div class="col-md-6">
                                            <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="addressip" type="text" placeholder="{{Saisir l'adresse IP}}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{{Port SSH}}
                                            <sup><i class="fas fa-question-circle tooltips" title="{{Port SSH (par défaut : 22)}}"></i></sup>
                                        </label>
                                        <div class="col-md-6">
                                            <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="portssh" type="text" placeholder="{{Saisir le port SSH}}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{{Timeout SSH}}
                                            <sup><i class="fas fa-question-circle tooltips" title="{{Durée maximale (en secondes) avant expiration de la connexion SSH (par défaut : 30s)}}"></i></sup>
                                        </label>
                                        <div class="col-md-6">
                                            <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="timeoutssh" type="text" placeholder="{{Saisir le timeout SSH}}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{{Identifiant}}</label>
                                        <div class="col-md-6">
                                            <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user" type="text" autocomplete="ssh-user" placeholder="{{Saisir le login}}" />
                                        </div>
                                    </div>
                                    <div class="remote-pwd" style="display:none;">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">{{Mot de passe}}</label>
                                            <div class="col-md-6 input-group">
                                                <input type="password" id="ssh-password" class="eqLogicAttr form-control roundedLeft" data-l1key="configuration" data-l2key="password" placeholder="{{Saisir le password}}" />
                                                <span class="input-group-btn">
											        <a class="btn btn-default form-control roundedRight" onclick="toggleSSHPassword()"><i id="btnToggleSSHPasswordIcon" class="fas fa-eye"></i></a>
										        </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="remote-key" style="display:none;">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">{{Passphrase}}
                                                <sup><i class="fas fa-question-circle tooltips" title="{{Optionnel : Phrase secrète pour la clé SSH}}"></i></sup>
                                            </label>
                                            <div class="col-md-6 input-group">
                                                <input type="password" id="ssh-passphrase" class="eqLogicAttr form-control roundedLeft" data-l1key="configuration" data-l2key="ssh-passphrase" placeholder="{{Saisir la passphrase SSH}}" />
                                                <span class="input-group-btn">
											        <a class="btn btn-default form-control roundedRight" onclick="toggleSSHPassphrase()"><i id="btnToggleSSHPassphraseIcon" class="fas fa-eye"></i></a>
										        </span>
                                            </div>
                                        </div>    
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">{{Clé SSH}}</label>
                                            <div class="col-md-8">
                                                <textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ssh-key" placeholder="{{Saisir la clé SSH}}" wrap="off" spellcheck="false"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <!-- Partie droite de l'onglet "Équipement" -->
                    <div class="col-xs-6">
                        <form class="form-horizontal">
                            <fieldset>
                                <legend>{{Auto-Actualisation (Cron)}} :</legend>
                                <div class="form-group">
                                    <label class="col-md-2 control-label" >{{Activer}}
                                        <sup><i class="fas fa-question-circle tooltips" title="{{Si cette option n'est pas cochée, le cron par défaut du plugin sera utilisé}}"></i></sup>
                                    </label>
                                    <div class="col-md-8">
                                        <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="pull_use_custom" />
                                        <span style="font-size: 85%;">({{A cocher pour spécifier une auto-actualisation des commandes personnalisée}})</span>
                                    </div>
                                </div>
                                <div class="form-group pull_class" style="display:none;">
                                    <label class="col-md-2 control-label" >{{Cron Personnalisé}}
                                        <sup><i class="fas fa-question-circle tooltips" title="{{Fréquence de rafraîchissement des commandes de l'équipement}}"></i></sup>
                                    </label>
                                    <div class="col-sm-6">
		                                <div class="input-group">
                                            <input type="text" class="eqLogicAttr form-control roundedLeft" data-l1key="configuration" data-l2key="pull_cron" placeholder="{{Cliquer sur ? pour afficher l'assistant cron}}">
                                            <span class="input-group-btn">
                                                <a class="btn btn-default cursor jeeHelper roundedRight" data-helper="cron" title="Assistant cron">
                                                    <i class="fas fa-question-circle"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Onglet des commandes de l'équipement -->
			<div role="tabpanel" class="tab-pane" id="commandtab">
                <br><br>
                <div class="table-responsive">
				    <table id="table_cmd" class="table table-bordered table-condensed">
					    <thead>
						    <tr>
							    <th class="hidden-xs" style="min-width:50px;width:70px;">{{Id}}</th>
							    <th style="min-width:220px;width:250px;">{{Nom}}</th>
                                <th style="min-width:100px;">{{Options}}</th>
                                <th style="min-width:100px;width:150px;">{{Type}}</th>
                                <th style="min-width:250px;">{{Etat}}</th>
							    <th style="min-width:130px;width:150px;">{{Actions}}</th>
						    </tr>
					    </thead>
					    <tbody>
					    </tbody>
				    </table>
                </div>
			</div><!-- /.tabpanel #commandtab-->
        </div><!-- /.tab-content -->
    </div><!-- /.eqLogic -->
</div><!-- /.row row-overflow -->

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, id_du_plugin) -->
<?php include_file('desktop', 'sshmanager', 'js', 'sshmanager'); ?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js'); ?>
