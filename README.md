## Laravel Media Manager

Easily associate your Media with Eloquent Models

- [Installation](#Installation)
- [Usage](#Usage)


## Getting Started
Install the package

- `composer require shrestharikesh/laravel-media-manager`

Run the migration
- `php artisan migrate`

## Usage
Use the trait given by the package into any model:

```php
use Shrestharikesh\LaravelMediaManager\HasMedia;

class Post extends Model
{
    use HasMedia;
    // Rest of your code
}
```

Associate as many media as you want to the model:
```php
$post = Post::create($data);
$post->addMedia($request->image);
```
Or just one to each tag of the model:
```php
$post->addMedia($request->image)->deletePrevious();
```
Easily upload media to different tags:
```php
$post->addMedia($request->featured_image)->withTag('featured');
$post->addMedia($request->thumbnail_image)->withTag('thumbnail');
```
Get all the associated media easily:
```php
$post = Post::find(1);
$post->getMedia($request->featured_image); // Returns medias from all topics
$post->getMedia($request->featured_image, 'default'); // Medias uploaded without any tag
$post->getMedia($request->featured_image, 'featured')->withAltText('Featured Image');
```
Or just the one:
```php
$post->getFirstMedia($request->featured_image);
$post->getFirstMedia($request->featured_image, 'featured');
```

Show the media easily to your blade files:

```php
<img src="{{$featured_post->getFirstMedia('featured')?->url}}"/>
```

You are about to remove your model and want to remove all associated medias? 
```php
$post = Post::find(1);
$post->deleteMedia($request->featured_image);
$post->delete();
```
Or just delete medias from specific topic:
```php
$post = Post::find(1);
$post->deleteMedia('featured');
```
Maybe just the one you don't need anymore:
```php
$post->deleteSpecificMedia(1); // Pass the media id
```

Oh no! you've found bugs? Feel free to [create an issue on GitHub](https://github.com/shrestharikesh/laravel-media-manager/issues), we'll fix it ASAP.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
