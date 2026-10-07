const { x64hash128 } = require("./encryptions/x64hash");
const { calculateMd5_b64 } = require("./encryptions/md5");

class RiskData {
    constructor(
        userAgent,
        language,
        colorDepth,
        deviceMemory,
        hardwareConcurrency,
        width,
        height,
        availWidth,
        availHeight,
        timezoneOffset,
        timezone,
        platform,
        cpuClass=undefined,
        doNotTrack=null
    ) {
        this.userAgent = userAgent;
        this.language = language;
        this.colorDepth = colorDepth;
        this.deviceMemory = deviceMemory;
        this.hardwareConcurrency = hardwareConcurrency;
        this.width = width;
        this.height = height;
        this.availWidth = availWidth;
        this.availHeight = availHeight;
        this.timezoneOffset = timezoneOffset;
        this.timezone = timezone;
        this.platform = platform;
        this.cpuClass = cpuClass;
        this.doNotTrack = doNotTrack;
    }

    generate() {
        let data = {
            version: "1.0.0",
            deviceFingerprint: this.dfValue(),
            persistentCookie: [],
            components: this.generateComponents()
        }
        // return in base64
        return Buffer.from(JSON.stringify(data)).toString('base64');
    }

    dfValue() {
        return `${this.generateFingerprint()}:40`
    }

    generateComponents() {
        return this.processDFPComponents([
            {
                "key": "REDACTED_CREDENTIAL",
                "value": this.userAgent
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": false
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": this.language
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": this.colorDepth
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": this.deviceMemory
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": 2
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": this.hardwareConcurrency
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": [
                    this.width,
                    this.height
                ]
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": [
                    this.availWidth,
                    this.availHeight
                ]
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": this.timezoneOffset
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": this.timezone
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": true
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": true
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": true
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": false
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": false
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": "not available"
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": this.platform
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": "not available"
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": [
                    [
                        "PDF Viewer",
                        "Portable Document Format",
                        [
                            [
                                "application/pdf",
                                "pdf"
                            ],
                            [
                                "text/pdf",
                                "pdf"
                            ]
                        ]
                    ],
                    [
                        "Chrome PDF Viewer",
                        "Portable Document Format",
                        [
                            [
                                "application/pdf",
                                "pdf"
                            ],
                            [
                                "text/pdf",
                                "pdf"
                            ]
                        ]
                    ],
                    [
                        "Chromium PDF Viewer",
                        "Portable Document Format",
                        [
                            [
                                "application/pdf",
                                "pdf"
                            ],
                            [
                                "text/pdf",
                                "pdf"
                            ]
                        ]
                    ],
                    [
                        "Microsoft Edge PDF Viewer",
                        "Portable Document Format",
                        [
                            [
                                "application/pdf",
                                "pdf"
                            ],
                            [
                                "text/pdf",
                                "pdf"
                            ]
                        ]
                    ],
                    [
                        "WebKit built-in PDF",
                        "Portable Document Format",
                        [
                            [
                                "application/pdf",
                                "pdf"
                            ],
                            [
                                "text/pdf",
                                "pdf"
                            ]
                        ]
                    ]
                ]
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": [
                    "canvas winding:yes",
                    `canvas fp:data:image/png;base64,REDACTED_OPAQUE_LITERAL=`
                ]
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": "not available"
            },
            {
                "key": "REDACTED_CREDENTIAL"
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": false
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": false
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": false
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": false
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": false
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": [
                    "Andale Mono",
                    "Arial",
                    "Arial Black",
                    "Arial Hebrew",
                    "Arial Narrow",
                    "Arial Rounded MT Bold",
                    "Arial Unicode MS",
                    "Calibri",
                    "Comic Sans MS",
                    "Courier",
                    "Courier New",
                    "Geneva",
                    "Georgia",
                    "Helvetica",
                    "Helvetica Neue",
                    "Impact",
                    "LUCIDA GRANDE",
                    "Microsoft Sans Serif",
                    "Monaco",
                    "Palatino",
                    "Tahoma",
                    "Times",
                    "Times New Roman",
                    "Trebuchet MS",
                    "Verdana",
                    "Wingdings",
                    "Wingdings 2",
                    "Wingdings 3"
                ]
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": "124.REDACTED_NUMERIC_IDENTIFIER"
            },
            {
                "key": "REDACTED_CREDENTIAL",
                "value": [
                    "id=;gid=;audioinput;",
                    "id=;gid=;videoinput;",
                    "id=;gid=;audiooutput;"
                ]
            }
        ])
    }

    processDFPComponents(components) {
        var parsedComponents = {}
        var key, val;
        for (var i = 0; i < components.length; i++) {
            val = components[i].value;
            key = components[i].key;
            if (key == "screenResolution") {
                parsedComponents["screenWidth"] = val[0];
                parsedComponents["screenHeight"] = val[1];
                continue;
            }
            if (key == "availableScreenResolution") {
                parsedComponents["availableScreenWidth"] = val[0];
                parsedComponents["availableScreenHeight"] = val[1];
                continue;
            }
            if (Array.isArray(val)) {
                val = val.join('');
            }
            if (['not available', 'error', 'excluded'].indexOf(val) != -1) {
                //skip
            } else if (typeof val === "boolean") {
                parsedComponents[key] = val ? 1 : 0;
            } else if (typeof val === "number" || ["timezone", "language", "platform", "webglVendorAndRenderer"].indexOf(key) != -1) {
                parsedComponents[key] = val;
            } else {
                parsedComponents[key] = x64hash128(val);
            }

        }
        return parsedComponents;
    }

    padString(f, e) {
        if (f.length >= e) {
            return (f.substring(0, e));
        }
        for (var d = ''; d.length < e - f.length; d += '0') {
        }
        return (d.concat(f));
    }

    generateFingerprint() {
        var E = {};var s = {
            plugins: 10,
            nrOfPlugins: 3,
            fonts: 10,
            nrOfFonts: 3,
            timeZone: 10,
            video: 10,
            superCookies: 10,
            userAgent: 10,
            mimeTypes: 10,
            nrOfMimeTypes: 3,
            canvas: 10,
            cpuClass: 5,
            platform: 5,
            doNotTrack: 5,
            webglFp: 10,
            jsFonts: 10
        };        
        try {
            try {
                var B = { "nr": 5, "obj": "Plugin 0: Chrome PDF Viewer; Portable Document Format; internal-pdf-viewer; (Portable Document Format; application/pdf; pdf) (Portable Document Format; text/pdf; pdf). Plugin 1: Chromium PDF Viewer; Portable Document Format; internal-pdf-viewer; (Portable Document Format; application/pdf; pdf) (Portable Document Format; text/pdf; pdf). Plugin 2: Microsoft Edge PDF Viewer; Portable Document Format; internal-pdf-viewer; (Portable Document Format; application/pdf; pdf) (Portable Document Format; text/pdf; pdf). Plugin 3: PDF Viewer; Portable Document Format; internal-pdf-viewer; (Portable Document Format; application/pdf; pdf) (Portable Document Format; text/pdf; pdf). Plugin 4: WebKit built-in PDF; Portable Document Format; internal-pdf-viewer; (Portable Document Format; application/pdf; pdf) (Portable Document Format; text/pdf; pdf). " };
                E.plugins = this.padString(calculateMd5_b64(B.obj), s.plugins);
                E.nrOfPlugins = this.padString(String(B.nr), s.nrOfPlugins);
            } catch (u) {
                console.log('### RiskData::dfGetProp:: plugins error=', u);
                E.plugins = this.padString('', s.plugins);
                E.nrOfPlugins = this.padString('', s.nrOfPlugins);
            }
            E.fonts = this.padString('', s.fonts);
            E.nrOfFonts = this.padString('', s.nrOfFonts);
            try {
                var e = new Date();
                e.setDate(1);
                e.setMonth(5);
                var C = e.getTimezoneOffset();
                e.setMonth(11);
                var D = e.getTimezoneOffset();
                E.timeZone = this.padString(calculateMd5_b64(C + "**" + D), s.timeZone);
            } catch (u) {
                console.log('### RiskData::dfGetProp:: timeZone error=', u);
                E.timeZone = this.padString('', s.timeZone);
            }
            try {
                E.video = this.padString(String((this.width + 7) * (this.height + 7) * this.colorDepth), s.video);
            } catch (u) {
                console.log('### RiskData::dfGetProp:: video error=', u);
                E.video = this.padString('', s.video);
            }
            E.superCookies = this.padString(calculateMd5_b64('DOM-LS: No, DOM-SS: No'), Math.floor(s.superCookies / 2)) + this.padString(calculateMd5_b64(', IE-UD: No'), Math.floor(s.superCookies / 2));
            E.userAgent = this.padString(calculateMd5_b64(this.userAgent), s.userAgent);
            var v = 'undefinedPortable Document Formatapplication/pdfpdfPortable Document Formattext/pdfpdf';
            var y = 2;
            E.mimeTypes = this.padString(calculateMd5_b64(v), s.mimeTypes);
            E.nrOfMimeTypes = this.padString(String(y), s.nrOfMimeTypes);
            E.canvas = this.padString(calculateMd5_b64(
                'data:image/png;base64,REDACTED_OPAQUE_LITERAL'
            ), s.canvas);
            E.cpuClass = (this.cpuClass) ? this.padString(calculateMd5_b64(this.cpuClass), s.cpuClass) : this.padString('', s.cpuClass);
            E.platform = (this.platform) ? this.padString(calculateMd5_b64(this.platform), s.platform) : this.padString('', s.platform);
            E.doNotTrack = (this.doNotTrack) ? this.padString(calculateMd5_b64(this.doNotTrack), s.doNotTrack) : this.padString('', s.doNotTrack);
            E.jsFonts = this.padString(calculateMd5_b64('Wingdings 3;Wingdings 2;Wingdings;Webdings;Verdana;Univers CE 55 Medium;Trebuchet MS;Times New Roman;Times;Tahoma;Symbol;Rockwell;PT Serif;PT Sans;Papyrus;Palatino;Modern No. 20;Microsoft Sans Serif;LUCIDA GRANDE;Impact;Humanst 521 Cn BT;Helvetica Neue;Helvetica;Goudy Bookletter 1911;Gill Sans;GeoSlab 703 XBd BT;GeoSlab 703 Lt BT;Georgia;Futura;Exo 2;English 111 Vivace BT;Courier New;Courier;Copperplate;Comic Sans MS;Calibri;Bookshelf Symbol 7;Bodoni 72 Smallcaps;Bodoni 72 Oldstyle;Bodoni 72;Bauhaus 93;Baskerville;Arial Unicode MS;Arial Rounded MT Bold;Arial Narrow;Arial Hebrew;Arial Black;Arial;Andale Mono;American Typewriter'), s.jsFonts);
            E.webglFp = this.padString(calculateMd5_b64('0000000000'), s.webglFp);
            var A = 0,
                i;
            for (i in E) {
                if (E.hasOwnProperty(i)) {
                    A = 0;
                    try {
                        A = E[i].length;
                    } catch (u) {
                    }
                    if (typeof E[i] === 'undefined' || E[i] === null || A !== s[i]) {
                        E[i] = this.padString('', s[i]);
                    }
                }
            }
        } catch (w) {
            console.log('### RiskData::dfGetProp:: error=', w);
        }
        var f = '';
        f = E.plugins + E.nrOfPlugins + E.fonts + E.nrOfFonts + E.timeZone + E.video + E.superCookies + E.userAgent + E.mimeTypes + E.nrOfMimeTypes + E.canvas + E.cpuClass + E.platform + E.doNotTrack + E.webglFp + E.jsFonts;
        f = f.replace(/\+/g, 'G').replace(/\//g, 'D');
        return f;
    }
}

module.exports = RiskData;