<?php if ($error !== ""): ?>
    <div class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
<?php endif; ?>

<form method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, "UTF-8") ?>" class="tree-collection-form">
<?= csrf_field() ?>
    <label for="tree_type_count">How many tree types does this farm have? *</label>
    <input id="tree_type_count" name="tree_type_count" type="number" min="1" max="50" value="<?= (int)$treeTypeCount ?>" required>
    <p class="form-hint">Add one entry for each different type of tree. You can record up to 50 types.</p>

    <div id="tree-type-rows">
        <?php foreach ($trees as $index => $tree): ?>
            <fieldset class="tree-type-card">
                <legend>Tree Type <?= $index + 1 ?></legend>
                <div><label for="tree_type_<?= $index ?>">Tree Type *</label>
                <input id="tree_type_<?= $index ?>" name="trees[<?= $index ?>][tree_type]" maxlength="100" placeholder="e.g. Olive, Orange, Lemon" value="<?= htmlspecialchars($tree["tree_type"] ?? "", ENT_QUOTES, "UTF-8") ?>" required></div>
                <div><label for="quantity_<?= $index ?>">Quantity *</label>
                <input id="quantity_<?= $index ?>" type="number" name="trees[<?= $index ?>][quantity]" min="1" step="1" value="<?= htmlspecialchars((string)($tree["quantity"] ?? ""), ENT_QUOTES, "UTF-8") ?>" required></div>
                <div><label for="area_size_<?= $index ?>">Area Size (hectares) *</label>
                <input id="area_size_<?= $index ?>" type="number" name="trees[<?= $index ?>][area_size]" min="0.01" step="0.01" value="<?= htmlspecialchars((string)($tree["area_size"] ?? ""), ENT_QUOTES, "UTF-8") ?>" required></div>
                <div><label for="planting_year_<?= $index ?>">Planting Year *</label>
                <input id="planting_year_<?= $index ?>" type="number" name="trees[<?= $index ?>][planting_year]" min="1901" max="<?= (int)date("Y") ?>" value="<?= htmlspecialchars((string)($tree["planting_year"] ?? ""), ENT_QUOTES, "UTF-8") ?>" required></div>
            </fieldset>
        <?php endforeach; ?>
    </div>

    <button type="submit">Save Tree Types</button>
</form>

<script>
(() => {
    const countInput = document.getElementById('tree_type_count');
    const rows = document.getElementById('tree-type-rows');
    const maxYear = <?= (int)date("Y") ?>;

    function addField(label, name, type, min, step, placeholder = '') {
        const wrapper = document.createElement('div');
        const inputId = name.replace(/[^a-z0-9_]/gi, '_');
        const labelElement = document.createElement('label');
        labelElement.htmlFor = inputId;
        labelElement.textContent = label + ' *';
        const input = document.createElement('input');
        input.id = inputId;
        input.name = name;
        input.type = type;
        input.required = true;
        if (min !== null) input.min = min;
        if (step !== null) input.step = step;
        if (placeholder) input.placeholder = placeholder;
        if (label === 'Planting Year') input.max = maxYear;
        wrapper.append(labelElement, input);
        return wrapper;
    }

    function makeCard(index) {
        const card = document.createElement('fieldset');
        card.className = 'tree-type-card';
        const legend = document.createElement('legend');
        legend.textContent = 'Tree Type ' + (index + 1);
        card.append(legend);
        card.append(addField('Tree Type', `trees[${index}][tree_type]`, 'text', null, null, 'e.g. Olive, Orange, Lemon'));
        card.append(addField('Quantity', `trees[${index}][quantity]`, 'number', 1, 1));
        card.append(addField('Area Size (hectares)', `trees[${index}][area_size]`, 'number', 0.01, 0.01));
        card.append(addField('Planting Year', `trees[${index}][planting_year]`, 'number', 1901, 1));
        return card;
    }

    function resizeCards() {
        const target = Math.max(1, Math.min(50, parseInt(countInput.value, 10) || 1));
        countInput.value = target;
        while (rows.children.length > target) rows.lastElementChild.remove();
        while (rows.children.length < target) rows.append(makeCard(rows.children.length));
        Array.from(rows.children).forEach((card, index) => {
            card.querySelector('legend').textContent = 'Tree Type ' + (index + 1);
        });
    }

    countInput.addEventListener('input', resizeCards);
    resizeCards();
})();
</script>
