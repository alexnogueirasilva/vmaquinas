<?php $v->layout("_theme"); ?>

<div class="app_formbox app_widget">
    <form class="app_form" action="<?= url("/app/invoice/{$invoice->id}"); ?>" method="post">
        <input type="hidden" name="update" value="true"/>

        <label>
            <span class="field icon-leanpub">Titulo:</span>
            <input class="radius" type="text" name="title" value="<?= $invoice->titulo; ?>" required/>
        </label>

        <label>
            <span class="field icon-briefcase">Descrição:</span>
            <textarea name="description"><?= $invoice->descricao ?></textarea>
        </label>

        <div class="label_group">
            <label>
                <span class="field icon-filter">Dia de vencimento:</span>
                <input class="date" name="due_day" name="value"
                       value="<?= date_fmt($invoice->created_at, "d/m/Y"); ?>" required/>
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-filter">Categoria:</span>
                <select name="category">
                    <?php foreach ($categories as $category): ?>
                        <option <?= ($category->id == $invoice->tipo_id ? "selected" : ""); ?>
                                value="<?= $category->id; ?>">&ofcir; <?= $category->nome; ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>
                <span class="field icon-filter">Status:</span>
                <select name="status">
                        <option <?= ($invoice->status === 'open' ? "selected" : ''); ?> value="open">&ofcir; Aberto</option>
                        <option <?= ($invoice->status === 'closed' ? "selected" : ''); ?> value="closed">&ofcir; Fechado</option>
                </select>
            </label>
        </div>

        <div class="al-center">
            <div class="app_formbox_actions">
                <span data-invoiceremove="<?= url("/app/remove/{$invoice->id}"); ?>"
                      class="btn_remove transition icon-error">Excluir</span>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Atualizar</button>
                <a class="btn_back transition radius icon-sign-in" href="<?= url_back(); ?>" title="Voltar">Voltar</a>
            </div>
        </div>
    </form>
</div>