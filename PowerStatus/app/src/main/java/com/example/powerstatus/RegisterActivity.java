package com.example.powerstatus;

import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.rengwuxian.materialedittext.MaterialEditText;

import java.util.HashMap;
import java.util.Map;

public class RegisterActivity extends AppCompatActivity {
    MaterialEditText username,email,password;
    RadioGroup radioGroup;
    Button register;
    String website ;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        website = getResources().getString(R.string.website);
        username=findViewById(R.id.username);
        email=findViewById(R.id.email);
        password=findViewById(R.id.password);
        radioGroup=findViewById(R.id.radiogp);
        register=findViewById(R.id.register);
        register.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String txtUserName = username.getText().toString();
                String txtEmail = email.getText().toString();
                String txtPassword = password.getText().toString();
                if(TextUtils.isEmpty(txtUserName) || TextUtils.isEmpty(txtEmail) || TextUtils.isEmpty(txtPassword)  )
                {
                    Toast.makeText(RegisterActivity.this,"All Fields should be filled ",Toast.LENGTH_SHORT).show();
                }
                else {
                    int genderId = radioGroup.getCheckedRadioButtonId();
                    RadioButton selected_Gender =radioGroup.findViewById(genderId);
                    if (selected_Gender ==null)
                    {
                        Toast.makeText(RegisterActivity.this,"Select Gender Please  ",Toast.LENGTH_SHORT).show();
                    }
                    else{
                        String select_Gender =selected_Gender.getText().toString();
                        registerNewAccount(txtUserName,txtEmail,txtPassword,select_Gender);
                    }
                }
            }
        });
    }
    private void registerNewAccount(String username, String email , String password , String gender ){
        ProgressDialog progressDialog = new ProgressDialog(RegisterActivity.this);
        progressDialog.setCancelable(false);
        progressDialog.setIndeterminate(false);
        progressDialog.setTitle("Register New User ");
        progressDialog.show();
        String url= website+"/app/register.php";
        StringRequest request= new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {

            @Override
            public void onResponse(String response) {
               if (response.equals("Successfully Registered")){


                  progressDialog.dismiss();
                   Toast.makeText(RegisterActivity.this,response,Toast.LENGTH_SHORT).show();
                    startActivity(new Intent(RegisterActivity.this,MainActivity.class));
                    finish();

               }
               else
               {
                   progressDialog.dismiss();
                   Toast.makeText(RegisterActivity.this, response ,Toast.LENGTH_SHORT).show();

               }


            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                progressDialog.dismiss();
                Toast.makeText(RegisterActivity.this, error.toString() ,Toast.LENGTH_SHORT).show();
            }
        }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                HashMap<String,String > param = new HashMap<>();
                param.put("username",username);
                param.put("email",email);
                param.put("password",password);
                param.put("gender",gender);
                return param;
            }
        };
        request.setRetryPolicy(new DefaultRetryPolicy(3000,DefaultRetryPolicy.DEFAULT_MAX_RETRIES,DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        MySingleton.getmInstance(RegisterActivity.this).addToRequestQueue(request);




    }
}