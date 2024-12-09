<form action="{{route('updated')}}" method="post">
    @csrf
    @method('PUT')
    <input type="text" name="name" id="" placeholder="Name">
    <input type="text" name="price" id="" placeholder="prix">
    <input type="text" name="categorie_id" id="" placeholder="cate_id">
    <input type="file" name="image_path" id="" placeholder="image">
    <textarea name="description" id="" cols="30" rows="10" placeholder="description"></textarea>
    <button type="submit">Updated</button>
</form>