<label>Nombre<input required maxlength="100" name="nombre" value="<?= e($values['nombre']) ?>"></label>
<label>Teléfono<input required maxlength="25" name="telefono" type="tel" value="<?= e($values['telefono']) ?>"></label>
<label>Correo electrónico<input required maxlength="190" name="correo" type="email" value="<?= e($values['correo']) ?>"></label>
<label>Ciudad<input required maxlength="100" name="ciudad" value="<?= e($values['ciudad']) ?>"></label>
<label class="checkbox-label favorite-checkbox"><input type="checkbox" name="favorito" value="1" <?= (int)($values['favorito'] ?? 0) === 1 ? 'checked' : '' ?>> ★ Marcar como favorito</label>
