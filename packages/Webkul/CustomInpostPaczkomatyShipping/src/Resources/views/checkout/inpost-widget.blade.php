<div id="inpost-map-container" v-if="selectedMethod === 'custom_inpostpaczkomaty_shipping_custom_inpostpaczkomaty_shipping'">
    <p><strong>Wybierz Paczkomat:</strong></p>

    <div id="geowidget" style="height:400px;"></div>

    <div v-if="selectedLocker" class="selected-paczkomat-info">
        Wybrano: @{{ selectedLocker }}
    </div>
</div>