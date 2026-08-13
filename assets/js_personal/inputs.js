$(".decimal2").inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 2,
        autoGroup: true,
        prefix: '', //Space after $, this will not truncate the first character.
        rightAlign: true,
        oncleared: function () { self.Value(''); }
    });
$(".decimal1").inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 1,
        autoGroup: true,
        prefix: '', //Space after $, this will not truncate the first character.
        rightAlign: true,
        oncleared: function () { self.Value(''); }
    });
$(".entero").inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 0,
        autoGroup: true,
        prefix: '', //Space after $, this will not truncate the first character.
        rightAlign: true,
        oncleared: function () { self.Value(''); }
    });
$(".enteroOnly").inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: "",
        digits: 0,
        autoGroup: true,
        prefix: '', //Space after $, this will not truncate the first character.
        rightAlign: true,
        oncleared: function () { self.Value(''); }
    });
$(".decimal3").inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 3,
        autoGroup: true,
        prefix: '', //Space after $, this will not truncate the first character.
        rightAlign: true,
        oncleared: function () { self.Value(''); }
    });
$(".decimal4").inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 4,
        autoGroup: true,
        prefix: '', //Space after $, this will not truncate the first character.
        rightAlign: true,
        oncleared: function () { self.Value(''); }
    });