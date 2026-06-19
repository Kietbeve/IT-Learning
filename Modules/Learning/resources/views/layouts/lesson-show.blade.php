@extends('layouts.user')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">


{{-- QUAY LẠI --}}
<div class="mb-5">

<button onclick="history.back()"
class="
flex items-center gap-2
bg-blue-50
text-blue-600
px-4 py-2
rounded-xl
hover:bg-blue-100">

← Quay lại

</button>

</div>




{{-- HEADER --}}

<div class="
bg-white
border border-blue-100
rounded-3xl
shadow-sm
p-5 sm:p-7
mb-6">


<div class="
flex
flex-col
md:flex-row
justify-between
gap-5">


<div>


<div class="flex flex-wrap gap-2 mb-3">


<span class="
bg-blue-100
text-blue-600
px-3 py-1
rounded-full
text-sm
font-semibold">

Bài {{ sprintf("%02d",$lessonId ?? 1) }}

</span>



<span class="text-gray-500 text-sm flex items-center">

{{ $categoryName ?? 'Lộ trình học tập' }}

</span>


</div>




<h1 class="
text-2xl
sm:text-3xl
font-bold
text-blue-700">

{{ $lessonTitle ?? 'Nội dung bài học' }}

</h1>



<p class="
text-gray-500
mt-3">

Học tài liệu bên dưới để hoàn thành bài học.

</p>



</div>




<div class="
flex
items-center
gap-3
md:flex-col
md:text-center">


<div class="
w-14
h-14
rounded-full
bg-blue-600
text-white
flex
items-center
justify-center
font-bold
text-xl">

IT

</div>


<div>

<p class="font-semibold text-gray-700">
IT-Learning
</p>

<p class="text-sm text-gray-400">
Giảng viên hướng dẫn
</p>


</div>


</div>



</div>


</div>





{{-- PDF --}}


<div class="
bg-white
border border-blue-100
rounded-3xl
shadow-sm
p-5
mb-6">


<div class="
flex
flex-col
sm:flex-row
justify-between
items-center
gap-3
mb-5">


<h2 class="
text-xl
font-bold
text-gray-800">

📄 Tài liệu bài học

</h2>



<a href="{{ asset('storage/learning/'.$pdfFile) }}"
download

class="
bg-blue-50
text-blue-600
px-5
py-2
rounded-xl
hover:bg-blue-100">

⬇ Tải PDF

</a>



</div>





<div id="pdfBox"


class="
h-[500px]
sm:h-[650px]
overflow-y-auto
overflow-x-hidden
border
rounded-2xl
bg-gray-50
p-2 sm:p-4">


<div id="pdfViewer">

</div>


</div>




<p id="pdfMessage"

class="
mt-4
text-center
text-sm
text-amber-600
bg-amber-50
rounded-xl
py-2">

💡 Hãy cuộn xuống cuối tài liệu để mở khóa hoàn thành

</p>



</div>







{{-- HOÀN THÀNH --}}


<button id="completeBtn"

disabled

class="
w-full
py-4
rounded-2xl
bg-gray-300
text-white
font-bold
text-lg
cursor-not-allowed
transition">


✓ Hoàn thành bài học


</button>









{{-- BÌNH LUẬN --}}


<div class="
bg-white
border border-blue-100
rounded-3xl
shadow-sm
p-5
mt-6">


<h2 class="
text-xl
font-bold
text-gray-800
mb-5">

💬 Trao đổi trong lớp học

</h2>




<div class="flex gap-3">


<div class="
w-11
h-11
rounded-full
bg-blue-600
text-white
flex
items-center
justify-center
font-bold">

SV

</div>




<div class="flex-1">


<textarea id="commentInput"

rows="3"

class="
w-full
border
rounded-2xl
p-3
focus:ring-2
focus:ring-blue-400"

placeholder="Nhập câu hỏi của bạn...">

</textarea>



<button id="sendComment"

class="
mt-3
bg-blue-600
text-white
px-5
py-2
rounded-xl">

Gửi

</button>



</div>


</div>





<div id="commentList"

class="mt-6 space-y-4">


<div class="flex gap-3">


<div class="
w-11
h-11
rounded-full
bg-blue-500
text-white
flex
items-center
justify-center
font-bold">

AN

</div>


<div class="
bg-blue-50
rounded-2xl
p-4
flex-1">


<b>An Nguyễn</b>


<p class="
text-gray-600
text-sm
mt-2">

Tài liệu rất dễ hiểu.

</p>


</div>


</div>



</div>


</div>





</div>





{{-- PDF JS --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>




<script>


const pdfBox =
document.getElementById('pdfBox');


const pdfViewer =
document.getElementById('pdfViewer');


const btn =
document.getElementById('completeBtn');


const message =
document.getElementById('pdfMessage');



let finished=false;



// LOAD PDF


const url =
"{{ asset('storage/learning/'.$pdfFile) }}";



pdfjsLib.getDocument(url)
.promise
.then(pdf=>{


for(
let i=1;
i<=pdf.numPages;
i++
){


pdf.getPage(i)
.then(page=>{


let containerWidth =
pdfBox.clientWidth - 32;


// lấy kích thước PDF gốc
let originalViewport =
page.getViewport({
    scale:1
});


// tính scale vừa với màn hình
let scale =
containerWidth / originalViewport.width;



// giới hạn scale
if(scale > 1.5){
    scale = 1.5;
}


let viewport =
page.getViewport({
    scale: scale
});



let canvas =
document.createElement('canvas');


let ctx =
canvas.getContext('2d');


canvas.style.width =
"100%";


canvas.style.height =
"auto";


canvas.width =
viewport.width;


canvas.height =
viewport.height;


pdfViewer.appendChild(canvas);



page.render({

canvasContext:ctx,

viewport:viewport

});



});

}


});





// KIỂM TRA CUỘN HẾT PDF


pdfBox.addEventListener(
'scroll',
()=>{


let bottom =
pdfBox.scrollTop
+
pdfBox.clientHeight
>=
pdfBox.scrollHeight - 20;



if(bottom && !finished){


finished=true;


btn.disabled=false;


btn.classList.remove(
'bg-gray-300',
'cursor-not-allowed'
);



btn.classList.add(
'bg-blue-600',
'hover:bg-blue-700'
);



message.innerHTML =
"🎉 Đã đọc hết tài liệu. Có thể hoàn thành bài học";


}



});







// HOÀN THÀNH


btn.onclick=function(){


btn.innerHTML =
"✓ Đã hoàn thành";


btn.classList.remove(
'bg-blue-600'
);


btn.classList.add(
'bg-green-500'
);



setTimeout(()=>{


history.back();


},1000);


}






// COMMENT


document.getElementById('sendComment')
.onclick=function(){


let input =
document.getElementById('commentInput');


let text =
input.value.trim();



if(text=="")
return;



let div =
document.createElement('div');



div.className =
"flex gap-3";



div.innerHTML=`

<div class="w-11 h-11 rounded-full bg-green-500 text-white flex items-center justify-center font-bold">
Bạn
</div>


<div class="bg-green-50 rounded-2xl p-4 flex-1">

<b>Bạn</b>

<p class="text-gray-600 text-sm mt-2">
${text}
</p>

</div>

`;



document.getElementById('commentList')
.prepend(div);



input.value="";


}



</script>


@endsection