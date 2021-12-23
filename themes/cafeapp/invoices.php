<?php $v->layout("_theme"); ?>

<div class="app_launch_header">
    <form class="app_launch_form_filter app_form" action="<?= url("/app/filter"); ?>" method="post">
        <input type="hidden" name="filter" value="<?= $status; ?>" />

        <select name="category">
            <option value="all">Todas</option>
            <?php foreach ($categories as $category) : ?>
                <option <?= (!empty($filter->category) && $filter->category == $category->id ? "selected" : ""); ?> value="<?= $category->id; ?>"><?= $category->nome; ?></option>
            <?php endforeach; ?>
        </select>

        <select name="status">
            <option value="all" <?= (empty($filter->status) ? "selected" : ""); ?>>Todas</option>
            <option value="open" <?= (!empty($filter->status) && $filter->status == "open" ? "selected" : ""); ?>><?= ($status == 'open' ? "Atividade aberta" : "Chamado Aberto"); ?></option>
            <option value="closed" <?= (!empty($filter->status) && $filter->status == "closed" ? "selected" : ""); ?>><?= ($status == 'closed' ? "Atividades fechadas" : "Chamado Fechado"); ?></option>
        </select>

        <input list="datelist" type="text" class="radius mask-month" name="date" placeholder="<?= (!empty($filter->date) ? $filter->date : date("m/Y")); ?>">

        <datalist id="datelist">
            <?php for ($range = -2; $range <= 2; $range++) :
                $dateRange = date("m/Y", strtotime(date("Y-m-01") . "+{$range}month"));
            ?>
                <option value="<?= $dateRange; ?>" />
            <?php endfor; ?>
        </datalist>

        <button class="filter radius transition icon-filter icon-notext"></button>
    </form>

    <div class="app_launch_btn income radius transition icon-plus-circle" data-modalopen=".app_modal_income"> Criar Atividade
    </div>
</div>

<section class="app_launch_box">
    <?php if (!$invoices) : ?>
        <?php if (empty($filter->status)) : ?>
            <div class="message info icon-info">Ainda não existem Atividades
                em <?= ($type == "open" ? "aberto" : "fechado"); ?>

            </div>
        <?php else : ?>
            <div class="message info icon-info">Não existem Atividades
                <?= ($type == "closed" ? "fechado" : "Fechadas"); ?>
                para o filtro aplicado.
            </div>
        <?php endif; ?>
    <?php else : ?>
        <div class="app_launch_item header">
            <p class="desc">Titulo</p>
            <p class="date">Descrção</p>
            <p class="category">Categoria</p>
            <p class="enrollment">Data</p>
            <p class="price">Status</p>
        </div>
        <?php
        $closed = 0;
        $open = 0;
        foreach ($invoices as $invoice) :
        ?>
            <article class="app_launch_item">
                <p class="desc app_invoice_link transition">
                    <a title="<?= $invoice->titulo; ?>" href="<?= url("/app/chamado/{$invoice->id}"); ?>"><?= str_limit_words(
                                                                                                                $invoice->titulo,
                                                                                                                3,
                                                                                                                "&nbsp;<span class='icon-info icon-notext'></span>"
                                                                                                            ) ?></a>
                </p>
                <p class="category"><?= str_limit_words($invoice->descricao, 3) ?></p>
                <p class="category"><?= $invoice->category()->nome; ?></p>
                <p class="date">Dia <?= date_fmt($invoice->created_at, "d/m/Y"); ?></p>
                <p class="price">
                    <?php if ($invoice->status == 'closed') : ?>
                        <span class="check <?= $type; ?> icon-thumbs-o-down transition" data-toggleclass="active icon-thumbs-o-down icon-thumbs-o-up" data-onpaid="<?= url("/app/onpaid"); ?>" data-date="<?= ($filter->date ?? date("m/Y")); ?>" data-invoice="<?= $invoice->id; ?>"></span>
                    <?php else : ?>
                        <span class="check <?= $type; ?> icon-thumbs-o-up transition" data-toggleclass="active icon-thumbs-o-down icon-thumbs-o-up" data-onpaid="<?= url("/app/onpaid"); ?>" data-date="<?= ($filter->date ?? date("m/Y")); ?>" data-invoice="<?= $invoice->id; ?>"></span>
                    <?php endif; ?>
                </p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>