<div
    v-if="isInpostSelected"
    class="mt-6"
>
    <div ref="geowidgetContainer" style="height:400px;"></div>

    <div v-if="selectedLocker" class="mt-3 p-3 bg-green-600 text-white rounded">
        Wybrano: @{{ selectedLocker }}
    </div>
</div>