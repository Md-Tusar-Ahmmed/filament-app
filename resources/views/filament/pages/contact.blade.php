<x-filament-panels::page>
    <x-filament-panels::form style="row-gap:5px;">
        <form action="{{route('contact')}}" method="POST">
    @csrf
    <p>Email<span style="color: red;">*</span></p>
    <input type="email" name="email" id="email" placeholder="Enter your email here" required
        style="border-radius: 4px; margin-bottom:15px;">

    <p>Subject<span style="color: red;">*</span></p>
    <input type="text" name="subject" placeholder="Enter a subject" required style="border-radius: 4px; margin-bottom:15px;">

    <p>Massage<span style="color: red;">*</span></p>
    <textarea name="msg" id="" column="5" rows="15" required placeholder="Enter your massage"
        style="border-radius: 4px; margin-bottom:15px;"></textarea>

    <button type="submit" class="btn btn-success align-left"
        style="width: 200px; padding:10px; border:1px #3498eb; border-radius:5px; background:#3498eb;">Send
        Massage</button>


        
    </x-filament-panels::form>
</x-filament-panels::page>





