<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @if( $getState ) 
    <iframe src="/storage/{{ $getState() }}" frameborder="0" width="800px" height="400px"></iframe>
    @endif
</x-dynamic-component>
