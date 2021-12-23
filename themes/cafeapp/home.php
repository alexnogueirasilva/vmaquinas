<?php $v->layout("_theme"); ?>
<div class="app_main_box">
    <section class="app_main_left">
        <article class="app_widget">
            <header class="app_widget_title">
                <h2 class="icon-bar-chart">Controle</h2>
            </header>
            <div id="control"></div>
        </article>

        <div class="app_main_left_fature">
            <article class="app_widget app_widget_balance">
                <header class="app_widget_title">
                    <h2 class="icon-calendar-minus-o">Tickets Abertos:</h2>
                </header>
                <div class="app_widget_content">
                    <?php if (!empty($income)): ?>
                        <?php foreach ($income as $incomeItem): ?>
                            <?= $v->insert("views/balance", ["invoice" => $incomeItem->data()]); ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="message success al-center icon-check-square-o">
                            No momento, não existem contas a receber.
                        </div>
                    <?php endif; ?>
                    <a href="<?= url("app/receber"); ?>" title="Receitas"
                       class="app_widget_more transition">+ Receitas</a>
                </div>
            </article>

            <article class="app_widget app_widget_balance">
                <header class="app_widget_title">
                    <h2 class="icon-calendar-check-o">Tickets Fechados:</h2>
                </header>
                <div class="app_widget_content">
                    <?php if (!empty($expense)): ?>
                        <?php foreach ($expense as $expenseItem): ?>
                            <?= $v->insert("views/balance", ["invoice" => $expenseItem->data()]); ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="message error al-center icon-check-square-o">
                            No momento, não existem contas a pagar.
                        </div>
                    <?php endif; ?>
                    <a href="<?= url("app/atividades"); ?>" title="Despesas"
                       class="app_widget_more transition">+ Despesas</a>
                </div>
            </article>
        </div>
    </section>
    <section class="app_main_right">
        <ul class="app_widget_shortcuts">
            <li class="income radius transition" data-modalopen=".app_modal_income">
                <p class="icon-plus-circle">Receita</p>
            </li>
        </ul>
    </section>