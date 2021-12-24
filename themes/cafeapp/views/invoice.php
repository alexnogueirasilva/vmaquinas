<div class="app_modal_box app_modal_<?= $type; ?>">
    <p class="title icon-calendar-check-o">Nova Atividade:</p>
    <form class="app_form" action="<?= url("/app/launch"); ?>" method="post">
        <label>
            <span class="field icon-leanpub">Titulo:</span>
            <input class="radius" type="text" name="title" placeholder="Ex: Erro 404" required/>
        </label>

        <div>
            <label>
                <span class="field icon-comments">Descrição:</span>
                <textarea class="icon-notext" type="text" name="description" required></textarea>
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-briefcase">Atividades:</span>
                <select name="type_activity">
                    <?php foreach ($activities as $activity): ?>
                        <option value="<?= $activity->id; ?>">&ofcir; <?= $activity->nome ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <button class="btn radius transition icon-check-square-o">
            Lançar <?= ($type == 'income' ? "Receita" : "Despesa"); ?></button>
    </form>
</div>