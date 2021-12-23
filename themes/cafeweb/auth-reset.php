<?php $v->layout("_theme"); ?>

<article class="auth">
    <div class="auth_content container content">
        <header class="auth_header">
            <h1>Criar nova senha</h1>
            <p>Informe e repita uma novas senha para recuperar o acesso</p>
        </header>

        <form class="auth_form"  action="<?= url("/recuperar/resetar"); ?>" method="post" enctype="multipart/form-data">
            <div class="ajax_response"><?= flash(); ?></div>
            <input type="hidden" name="code" value="<?= $code ?>">
            <?= csrf_input(); ?>
            <label>
                <div class="unlock-alt">
                    <span class="icon-envelope">Nova Senha:</span>
                    <span><a title="voltar e entrar" href="<?= url("/entrar"); ?>">Voltar e Entrar</a></span>
                </div>
                <input type="password" name="password" placeholder="Nova senha:"/>
            </label>

            <label>
                <div class="unlock-alt">
                    <span class="icon-envelope">Repita a nova senha:</span>
                </div>
                <input type="password" name="password_re" placeholder="Nova senha:"/>
            </label>

            <button class="auth_form_btn transition gradient gradient-green gradient-hover">Alterar senha</button>
        </form>
    </div>
</article>
