<!-- MODULE DESIGN & CODE GOES HERE -->

<div class="header tabs_header">
    <a class="selected" href="addProduct.html">Produkt</a>
    <a href="addSupplier.html">Dodavatel</a>
</div>
<div class="form">
    <div class="form_content">
        <form action="skript.php" method="get">
            Název nového produktu:
            <input class="text" type="text" name="nazev">
            <br><br>
            Hlavní kategorie:
            <select name="hl_kategorie">
                <option value="hs" selected="selected">Hlavní kategorie</option>
            </select>
            <br><br>
            Vedlejší kategorie:
            <select name="vedl_kategorie">
                <option value="hs" selected="selected">Vedlejší kategorie</option>
            </select>
            <br><br>Popis produktu:<br>
            <textarea name="popis" rows="3" ></textarea>
            <br><br>


            <input class="button" type="submit" value="PŘIDAT">
        </form>
    </div>

</div>

<?php
// TODO: Remove this.
// Display information about module, debugging only
echo $MH->_getModuleDescription();

?>
