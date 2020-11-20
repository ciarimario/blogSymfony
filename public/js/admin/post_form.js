const imageFile = document.getElementById("post_imageFile");
const img = new Image();
imageFile.parentNode.append(img);
img.style.width = "150px";

img.style.marginTop = "2rem";
img.style.objectFit = "cover";

imageFile.addEventListener("change", (event) => {
  const file = event.currentTarget.files[0];
  const label = event.currentTarget.parentNode.querySelector(
    '[for="post_imageFile"]'
  );

  label.textContent = file.name;

  const reader = new FileReader();
  reader.readAsDataURL(file);
  reader.addEventListener("load", () => {
    const imageUrl = reader.result;
    img.src = imageUrl;
    img.style.border = "3px solid green";
    img.style.height = "100px";
  });
});
