{{ Aire::open()
        ->route('site.applications.update',$application->id)
        ->enctype("multipart/form-data")
        ->post() }}
<div class="mt-6">
</div>
<div class="w-full text-center pb-8 ">
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 mx-4 p-2 transition duration-300 rounded-md text-white"
    name="status" value="order_delivered">
        товар доставлен
    </button>
    <button type="submit" class="bg-blue-500 hover:bg-red-700 mx-4 p-2 transition duration-300 rounded-md text-white"
    name="status" value="order_arrived">
        товар прибыл
    </button>
</div>
{{Aire::close()}}
