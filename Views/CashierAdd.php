<!DOCTYPE html>

<html lang="cs-cz">
<head>
    <meta charset="UTF-8"/>
    <base href="/localhost"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
</head>

<body>
<div class="searchGeneral">
    Rezervace pro událost - pokladní:
    <div>
        <form method="post">
            <h4>Výběr sedadla</h4>
            <div class="item">
                <label for="column">Sloupec</label>
                <input type="number" id="column" name="column" min="0" max="15" required>

                <label for="row">Rada</label>
                <input list="rows" name="row" id="row" required>

                <datalist id="rows">
                    <option value="A">
                    <option value="B">
                    <option value="C">
                    <option value="D">
                    <option value="E">
                    <option value="F">
                    <option value="G">
                </datalist>

            </div>
            <div class="item">

            </div>
            <div class="item">
                <input type="submit" value="Potvrdit">
                <input type=button value="Zpet" onclick="window.location.href='search'">
            </div>
        </form>

    </div>
</div>
</body>