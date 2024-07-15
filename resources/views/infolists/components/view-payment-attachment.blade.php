<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <iframe src="/storage/{{ $getState() }}" frameborder="0" width="800px" height="400px"></iframe>
</x-dynamic-component>
