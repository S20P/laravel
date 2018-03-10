<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">



<title>{{ MetaTag::get('title') }}</title>

  {!! MetaTag::tag('title') !!}
  {!! MetaTag::tag('description') !!}
  {!! MetaTag::openGraph() !!}

<!-- keywords -->
<meta name="keywords" content="funny apps, facebook apps, share, feeds, free apps">
